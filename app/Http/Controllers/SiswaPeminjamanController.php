<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()) {
            $riwayat = $request->query('tab') === 'riwayat';

            $items = $request->user()->peminjamans()->with('details.alat')
                ->when(
                    $riwayat,
                    fn ($q) => $q->whereIn('status', ['Dikembalikan', 'Ditolak']),
                    fn ($q) => $q->whereNotIn('status', ['Dikembalikan', 'Ditolak'])
                )
                ->latest()->paginate(10)->withQueryString();

            return view('siswa.peminjaman.index', [
                'items' => $items,
                'riwayat' => $riwayat,
            ]);
        }

        return redirect()->route('siswa.peminjaman.create');
    }

    public function create(Request $request)
    {
        $alats = Alat::with('kategori')->denganDipinjam()
            ->where('kondisi', 'Baik')->orderBy('nama_alat')->get()
            ->filter(fn ($a) => $a->tersedia > 0)->values();

        return view('siswa.peminjaman.create', [
            'alats' => $alats,
            'dipilih' => (int) $request->query('alat'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tanggal_pinjam' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_rencana_kembali' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0'],
        ], [
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'tanggal_rencana_kembali.required' => 'Tanggal rencana pengembalian wajib diisi.',
            'tanggal_rencana_kembali.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal pinjam.',
            'items.required' => 'Pilih minimal satu alat.',
        ]);

        $items = collect($data['items'])->map(fn ($j) => (int) $j)->filter(fn ($j) => $j > 0);

        if ($items->isEmpty()) {
            return back()->withInput()->withErrors(['items' => 'Pilih minimal satu alat dengan jumlah lebih dari 0.']);
        }

        $alats = Alat::denganDipinjam()->whereIn('id', $items->keys())->get()->keyBy('id');

        foreach ($items as $alatId => $jumlah) {
            $alat = $alats->get($alatId);
            if (! $alat || $alat->kondisi !== 'Baik') {
                return back()->withInput()->withErrors(['items' => 'Ada alat yang tidak valid atau tidak dapat dipinjam.']);
            }
            if ($jumlah > $alat->tersedia) {
                return back()->withInput()->withErrors(['items' => "Stok {$alat->nama_alat} tidak mencukupi (tersedia {$alat->tersedia})."]);
            }
        }

        $userId = $request->user()?->id;

        DB::transaction(function () use ($request, $data, $items, $userId) {
            $p = Peminjaman::create([
                'user_id' => $userId,
                'tanggal_pinjam' => $data['tanggal_pinjam'],
                'tanggal_rencana_kembali' => $data['tanggal_rencana_kembali'],
                'catatan' => $data['catatan'] ?? null,
                'status' => 'Menunggu',
            ]);
            foreach ($items as $alatId => $jumlah) {
                $p->details()->create(['alat_id' => $alatId, 'jumlah' => $jumlah]);
            }
            $p->riwayats()->create([
                'user_id' => $userId, 
                'status' => 'Menunggu', 
                'catatan' => 'Pengajuan dibuat oleh ' . ($request->user() ? $request->user()->name : 'Publik/Tamu')
            ]);
        });

        if ($request->user()) {
            return redirect()->route('siswa.peminjaman.index')->with('sukses', 'Pengajuan peminjaman berhasil dikirim. Menunggu verifikasi admin.');
        }

        return redirect()->route('beranda')->with('sukses', 'Pengajuan peminjaman berhasil dikirim! Menunggu verifikasi admin.');
    }

    public function show(Request $request, Peminjaman $peminjaman)
    {
        if ($request->user()) {
            abort_unless($peminjaman->user_id === $request->user()->id || $request->user()->role === 'admin', 403, 'Anda tidak dapat melihat data peminjaman milik pengguna lain.');
        } else {
            abort_unless(is_null($peminjaman->user_id), 403, 'Akses ditolak.');
        }

        $peminjaman->load('details.alat', 'riwayats.user');

        return view('siswa.peminjaman.show', compact('peminjaman'));
    }
}