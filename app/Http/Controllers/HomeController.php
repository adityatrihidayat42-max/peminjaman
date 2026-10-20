<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function beranda()
    {
        // Alat terpopuler = paling sering dipinjam (jumlah baris detail); cadangan: terbaru.
        $populer = Alat::with('kategori')->denganDipinjam()
            ->withCount('details')
            ->orderByDesc('details_count')->orderBy('nama_alat')
            ->take(5)->get();

        // Pengumuman bersifat statis (belum ada tabel pengumuman di PRD).
        $pengumuman = [
            ['tanggal' => '12 Apr 2025', 'judul' => 'Penambahan Alat Baru', 'isi' => 'Telah tersedia 3 unit laptop developer terbaru dengan spesifikasi lebih tinggi untuk mendukung kegiatan praktik.', 'ikon' => 'bi-laptop'],
            ['tanggal' => '8 Apr 2025', 'judul' => 'Jadwal Peminjaman Diperbarui', 'isi' => 'Mulai minggu ini, jadwal peminjaman alat hanya bisa dilakukan minimal 1 hari sebelum tanggal pengambilan.', 'ikon' => 'bi-router'],
            ['tanggal' => '2 Apr 2025', 'judul' => 'Workshop IoT dengan Arduino', 'isi' => 'Akan dilaksanakan workshop penggunaan Arduino. Segera daftar melalui menu kegiatan.', 'ikon' => 'bi-cpu'],
        ];

        return view('home', compact('populer', 'pengumuman'));
    }

    public function alat(Request $request)
    {
        $alats = Alat::with('kategori')->denganDipinjam()
            ->when($request->q, fn ($q, $v) => $q->where('nama_alat', 'like', "%{$v}%"))
            ->when($request->kategori, fn ($q, $v) => $q->where('kategori_id', $v))
            ->orderBy('nama_alat')->paginate(12)->withQueryString();

        return view('alat-katalog', [
            'alats' => $alats,
            'kategoris' => Kategori::orderBy('nama_kategori')->get(),
        ]);
    }

    public function tentang()
    {
        return view('tentang');
    }
}
