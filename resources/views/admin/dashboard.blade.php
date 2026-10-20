@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-sub">Selamat datang, {{ auth()->user()->name }}</p>
</div>

<div class="row g-3 mb-4">
    @foreach ([
        ['blue', 'bi-grid', $jenisAlat, 'Total Jenis Alat'],
        ['indigo', 'bi-boxes', $totalUnit, 'Total Unit Inventaris'],
        ['green', 'bi-check2-circle', $tersedia, 'Unit Tersedia'],
        ['blue', 'bi-box-arrow-up-right', $dipinjam, 'Unit Sedang Dipinjam'],
        ['orange', 'bi-hourglass-split', $menunggu, 'Menunggu Persetujuan'],
        ['red', 'bi-exclamation-triangle', $terlambat, 'Terlambat'],
    ] as [$warna, $ikon, $angka, $label])
        <div class="col-6 col-xl-2 col-md-4">
            <div class="card-sp stat">
                <div class="stat-icon {{ $warna }}"><i class="bi {{ $ikon }}"></i></div>
                <div><div class="num">{{ $angka }}</div><div class="lbl">{{ $label }}</div></div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card-sp">
            <div class="card-head"><h6>Peminjaman Terbaru</h6><a href="{{ route('admin.peminjaman.index') }}" class="small">Lihat semua</a></div>
            @include('partials.tabel-transaksi', ['items' => $terbaru, 'rute' => 'admin.peminjaman.show'])
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card-sp">
            <div class="card-head"><h6>Perlu Diproses</h6><span class="sp-badge sp-badge-warning">{{ $menunggu }} pengajuan</span></div>
            <div class="list-group list-group-flush">
                @forelse ($perluDiproses as $p)
                    <a href="{{ route('admin.peminjaman.show', $p) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $p->user->name }}</strong>
                            <small class="text-secondary">{{ $p->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="small text-secondary">{{ $p->ringkasan_alat }}</div>
                    </a>
                @empty
                    <div class="list-group-item text-secondary text-center py-4">Tidak ada pengajuan menunggu.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
