@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div><h1 class="page-title">Laporan Peminjaman</h1><p class="page-sub">Riwayat transaksi peminjaman dan pengembalian.</p></div>
    <a href="{{ route('laporan.cetak', request()->query()) }}" target="_blank" class="btn btn-sp"><i class="bi bi-printer me-1"></i> Cetak</a>
</div>

<div class="card-sp">
    <div class="card-pad border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-2"><label class="form-label small mb-0">Dari</label><input type="date" name="dari" value="{{ request('dari') }}" class="form-control"></div>
            <div class="col-md-2"><label class="form-label small mb-0">Sampai</label><input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control"></div>
            <div class="col-md-3"><label class="form-label small mb-0">Peminjam</label><input name="peminjam" value="{{ request('peminjam') }}" class="form-control" placeholder="Nama peminjam"></div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Status</label>
                <select name="status" class="form-select"><option value="">Semua</option>@foreach ($statuses as $s)<option @selected(request('status') === $s)>{{ $s }}</option>@endforeach</select>
            </div>
            <div class="col-md-3 d-flex gap-2 align-items-end"><button class="btn btn-sp flex-fill">Terapkan</button><a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">Reset</a></div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sp">
            <thead><tr><th>#</th><th>Peminjam</th><th>Alat</th><th>Pinjam</th><th>Rencana</th><th>Kembali</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($items as $p)
                <tr>
                    <td>{{ $p->id }}</td><td>{{ $p->user->name }}</td><td>{{ $p->ringkasan_alat }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                    <td>{{ $p->tanggal_rencana_kembali->format('d M Y') }}</td>
                    <td>{{ $p->tanggal_kembali_aktual?->format('d M Y') ?? '-' }}</td>
                    <td><span class="sp-badge {{ \App\Models\Peminjaman::badge($p->status) }}">{{ $p->status }}</span></td>
                    <td class="text-end"><a href="{{ route('laporan.show', $p) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-secondary py-4">Tidak ada data untuk filter ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-pad">{{ $items->links() }}</div>
</div>
@endsection
