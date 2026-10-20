<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        Peminjaman::tandaiTerlambat();

        $items = Peminjaman::with('user', 'details.alat')
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.peminjaman.index', ['items' => $items, 'statuses' => Peminjaman::STATUS]);
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load('user', 'details.alat', 'riwayats.user');

        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function setujui(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'Menunggu') {
            return back()->with('gagal', 'Hanya pengajuan berstatus Menunggu yang dapat disetujui.');
        }

        $galat = null;

        // Transaksi + penguncian baris alat agar dua persetujuan bersamaan
        // tidak memesan unit yang sama.
        DB::transaction(function () use ($request, $peminjaman, &$galat) {
            $peminjaman->load('details');
            $ids = $peminjaman->details->pluck('alat_id');

            Alat::whereIn('id', $ids)->lockForUpdate()->get();
            $alats = Alat::denganDipinjam()->whereIn('id', $ids)->get()->keyBy('id');

            foreach ($peminjaman->details as $d) {
                $alat = $alats[$d->alat_id];
                if ($d->jumlah > $alat->tersedia) {
                    $galat = "Stok {$alat->nama_alat} tidak mencukupi (tersedia {$alat->tersedia}, diminta {$d->jumlah}).";

                    return;
                }
            }

            $peminjaman->ubahStatus('Disetujui', $request->user()->id, 'Pengajuan disetujui admin.');
        });

        return $galat
            ? back()->with('gagal', $galat)
            : back()->with('sukses', 'Pengajuan disetujui. Stok alat sudah dipesan.');
    }

    public function tolak(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $data = $request->validate(['alasan' => ['required', 'string', 'max:500']], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        if ($peminjaman->status !== 'Menunggu') {
            return back()->with('gagal', 'Hanya pengajuan berstatus Menunggu yang dapat ditolak.');
        }

        $peminjaman->ubahStatus('Ditolak', $request->user()->id, $data['alasan']);

        return back()->with('sukses', 'Pengajuan ditolak.');
    }

    /** Alat diserahkan ke peminjam: Disetujui -> Dipinjam. */
    public function serahkan(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== 'Disetujui') {
            return back()->with('gagal', 'Hanya peminjaman berstatus Disetujui yang dapat diserahkan.');
        }

        $peminjaman->ubahStatus('Dipinjam', $request->user()->id, 'Alat diserahkan kepada peminjam.');

        return back()->with('sukses', 'Alat telah diserahkan. Status menjadi Dipinjam.');
    }
}
