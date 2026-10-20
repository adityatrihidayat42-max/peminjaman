@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div><h1 class="page-title">Dashboard</h1><p class="page-sub">Halo, {{ auth()->user()->name }}</p></div>
    <a href="{{ route('siswa.peminjaman.create') }}" class="btn btn-sp"><i class="bi bi-plus-lg me-1"></i> Ajukan Peminjaman</a>
</div>
<div class="row g-3 mb-4">
    @foreach ([['blue', 'bi-journal-arrow-up', $aktif, 'Peminjaman Aktif'], ['orange', 'bi-hourglass-split', $menunggu, 'Menunggu Persetujuan'], ['green', 'bi-check2-circle', $selesai, 'Selesai Dikembalikan']] as [$w, $i, $n, $l])
        <div class="col-md-4"><div class="card-sp stat"><div class="stat-icon {{ $w }}"><i class="bi {{ $i }}"></i></div><div><div class="num">{{ $n }}</div><div class="lbl">{{ $l }}</div></div></div></div>
    @endforeach
</div>
<div class="card-sp">
    <div class="card-head"><h6>Pengajuan Terbaru Saya</h6><a href="{{ route('siswa.peminjaman.index') }}" class="small">Lihat semua</a></div>
    <div class="table-responsive">
        <table class="table table-hover table-sp">
            <thead><tr><th>#</th><th>Alat</th><th>Pinjam</th><th>Rencana Kembali</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($terbaru as $p)
                <tr>
                    <td>{{ $p->id }}</td><td>{{ $p->ringkasan_alat }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td><td>{{ $p->tanggal_rencana_kembali->format('d M Y') }}</td>
                    <td><span class="sp-badge {{ \App\Models\Peminjaman::badge($p->status) }}">{{ $p->status }}</span></td>
                    <td class="text-end"><a href="{{ route('siswa.peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada pengajuan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
