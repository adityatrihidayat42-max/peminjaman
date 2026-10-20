<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $alats = Alat::with('kategori')->denganDipinjam()
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w->where('nama_alat', 'like', "%{$v}%")->orWhere('kode_alat', 'like', "%{$v}%")))
            ->when($request->kategori, fn ($q, $v) => $q->where('kategori_id', $v))
            ->when($request->kondisi, fn ($q, $v) => $q->where('kondisi', $v))
            ->orderBy('kode_alat')->paginate(10)->withQueryString();

        return view('admin.alat.index', [
            'alats' => $alats,
            'kategoris' => Kategori::orderBy('nama_kategori')->get(),
            'kondisis' => Alat::KONDISI,
        ]);
    }

    public function create()
    {
        return view('admin.alat.form', [
            'alat' => new Alat(['jumlah_total' => 1, 'kondisi' => 'Baik']),
            'kategoris' => Kategori::orderBy('nama_kategori')->get(),
            'kondisis' => Alat::KONDISI,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Alat::create($this->validasi($request));

        return redirect()->route('admin.alat.index')->with('sukses', 'Alat berhasil ditambahkan.');
    }

    public function show(Alat $alat)
    {
        $alat->load('kategori');
        $alat->loadSum(['details as jumlah_dipinjam' => fn ($d) => $d->whereHas('peminjaman', fn ($p) => $p->whereIn('status', \App\Models\Peminjaman::STATUS_AKTIF))], 'jumlah');
        $riwayat = $alat->details()->with('peminjaman.user')->latest()->take(10)->get();

        return view('admin.alat.show', compact('alat', 'riwayat'));
    }

    public function edit(Alat $alat)
    {
        return view('admin.alat.form', [
            'alat' => $alat,
            'kategoris' => Kategori::orderBy('nama_kategori')->get(),
            'kondisis' => Alat::KONDISI,
        ]);
    }

    public function update(Request $request, Alat $alat): RedirectResponse
    {
        $data = $this->validasi($request, $alat);

        // Stok tidak boleh turun di bawah unit yang sedang dipinjam/dipesan.
        $dipinjam = $alat->hitungDipinjam();
        if ($data['jumlah_total'] < $dipinjam) {
            return back()->withInput()->withErrors([
                'jumlah_total' => "Jumlah total tidak boleh kurang dari {$dipinjam} (unit yang sedang dipinjam/disetujui).",
            ]);
        }

        $alat->update($data);

        return redirect()->route('admin.alat.index')->with('sukses', 'Data alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat): RedirectResponse
    {
        // Transaksi lama dipertahankan untuk audit: alat yang pernah dipinjam tidak boleh dihapus.
        if ($alat->details()->exists()) {
            return back()->with('gagal', 'Alat tidak dapat dihapus karena sudah memiliki riwayat peminjaman.');
        }

        $alat->delete();

        return redirect()->route('admin.alat.index')->with('sukses', 'Alat berhasil dihapus.');
    }

    private function validasi(Request $request, ?Alat $alat = null): array
    {
        return $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'kode_alat' => ['required', 'string', 'max:30', Rule::unique('alats', 'kode_alat')->ignore($alat?->id)],
            'nama_alat' => ['required', 'string', 'max:255'],
            'jumlah_total' => ['required', 'integer', 'min:0', 'max:100000'],
            'kondisi' => ['required', Rule::in(Alat::KONDISI)],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kode_alat.required' => 'Kode alat wajib diisi.',
            'kode_alat.unique' => 'Kode alat sudah digunakan.',
            'nama_alat.required' => 'Nama alat wajib diisi.',
            'jumlah_total.required' => 'Jumlah total wajib diisi.',
            'jumlah_total.integer' => 'Jumlah total harus berupa angka bulat.',
            'jumlah_total.min' => 'Jumlah total tidak boleh negatif.',
        ]);
    }
}
