@extends('layouts.app')

@section('title', $alat->nama_alat)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="page-title">{{ $alat->nama_alat }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.alat.edit', $alat) }}" class="btn btn-sp">Edit</a>
        <a href="{{ route('admin.alat.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card-sp card-pad">
            <dl class="row mb-0">
                <dt class="col-5">Kode</dt><dd class="col-7">{{ $alat->kode_alat }}</dd>
                <dt class="col-5">Kategori</dt><dd class="col-7">{{ $alat->kategori->nama_kategori }}</dd>
                <dt class="col-5">Jumlah total</dt><dd class="col-7">{{ $alat->jumlah_total }}</dd>
                <dt class="col-5">Dipinjam</dt><dd class="col-7">{{ $alat->dipinjam }}</dd>
                <dt class="col-5">Tersedia</dt><dd class="col-7 fw-bold text-success">{{ $alat->tersedia }}</dd>
                <dt class="col-5">Kondisi</dt><dd class="col-7">{{ $alat->kondisi }}</dd>
                <dt class="col-5">Lokasi</dt><dd class="col-7">{{ $alat->lokasi ?: '-' }}</dd>
                <dt class="col-5">Keterangan</dt><dd class="col-7">{{ $alat->keterangan ?: '-' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card-sp">
            <div class="card-head"><h6>10 Transaksi Terakhir</h6></div>
            <div class="table-responsive">
                <table class="table table-sp">
                    <thead><tr><th>Peminjam</th><th>Tanggal</th><th class="text-end">Jml</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse ($riwayat as $d)
                        <tr>
                            <td>{{ $d->peminjaman->user->name }}</td>
                            <td>{{ $d->peminjaman->tanggal_pinjam->format('d M Y') }}</td>
                            <td class="text-end">{{ $d->jumlah }}</td>
                            <td><span class="sp-badge {{ \App\Models\Peminjaman::badge($d->peminjaman->status) }}">{{ $d->peminjaman->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">Belum pernah dipinjam.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
