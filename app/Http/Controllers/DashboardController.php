<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isGuru()) {
            return redirect()->route('laporan.index');
        }

        if ($user->isSiswa()) {
            $milik = $user->peminjamans();

            return view('siswa.dashboard', [
                'aktif' => (clone $milik)->whereIn('status', ['Menunggu', 'Disetujui', 'Dipinjam', 'Terlambat'])->count(),
                'menunggu' => (clone $milik)->where('status', 'Menunggu')->count(),
                'selesai' => (clone $milik)->where('status', 'Dikembalikan')->count(),
                'terbaru' => $user->peminjamans()->with('details.alat')->latest()->take(5)->get(),
            ]);
        }

        Peminjaman::tandaiTerlambat();

        $totalUnit = (int) Alat::sum('jumlah_total');
        $dipinjam = (int) DetailPeminjaman::whereHas('peminjaman', fn ($p) => $p->whereIn('status', Peminjaman::STATUS_AKTIF))->sum('jumlah');

        return view('admin.dashboard', [
            'jenisAlat' => Alat::count(),
            'totalUnit' => $totalUnit,
            'dipinjam' => $dipinjam,
            'tersedia' => max(0, $totalUnit - $dipinjam),
            'menunggu' => Peminjaman::where('status', 'Menunggu')->count(),
            'terlambat' => Peminjaman::where('status', 'Terlambat')->count(),
            'perluDiproses' => Peminjaman::with('user', 'details.alat')->where('status', 'Menunggu')->oldest()->take(5)->get(),
            'terbaru' => Peminjaman::with('user', 'details.alat')->latest()->take(6)->get(),
        ]);
    }
}
