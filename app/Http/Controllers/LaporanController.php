<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private function query(Request $request)
    {
        return Peminjaman::with('user', 'details.alat')
            ->when($request->dari, fn ($q, $v) => $q->whereDate('tanggal_pinjam', '>=', $v))
            ->when($request->sampai, fn ($q, $v) => $q->whereDate('tanggal_pinjam', '<=', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->peminjam, fn ($q, $v) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$v}%")))
            ->latest('tanggal_pinjam')->latest('id');
    }

    public function index(Request $request)
    {
        Peminjaman::tandaiTerlambat();

        return view('laporan.index', [
            'items' => $this->query($request)->paginate(15)->withQueryString(),
            'statuses' => Peminjaman::STATUS,
        ]);
    }

    public function cetak(Request $request)
    {
        return view('laporan.cetak', [
            'items' => $this->query($request)->get(),
            'filter' => $request->only(['dari', 'sampai', 'status', 'peminjam']),
        ]);
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load('user', 'details.alat', 'riwayats.user');

        return view('laporan.show', compact('peminjaman'));
    }
}
