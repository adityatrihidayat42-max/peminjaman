@extends('layouts.app')

@section('title', 'Detail Peminjaman #' . $peminjaman->id)

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h1 class="page-title">Peminjaman #{{ $peminjaman->id }}</h1>
    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

@include('partials.detail-transaksi')

@if ($peminjaman->status === 'Menunggu')
    <div class="card-sp card-pad mt-3" id="tolak">
        <div class="row g-3 align-items-start">
            <div class="col-md-3">
                <form method="POST" action="{{ route('admin.peminjaman.setujui', $peminjaman) }}">@csrf
                    <button class="btn btn-success w-100"><i class="bi bi-check-lg me-1"></i> Setujui</button>
                </form>
            </div>
            <div class="col-md-9">
                <form method="POST" action="{{ route('admin.peminjaman.tolak', $peminjaman) }}" class="d-flex gap-2">@csrf
                    <input name="alasan" class="form-control" placeholder="Alasan penolakan (wajib)" required>
                    <button class="btn btn-danger text-nowrap">Tolak</button>
                </form>
            </div>
        </div>
    </div>
@elseif ($peminjaman->status === 'Disetujui')
    <div class="card-sp card-pad mt-3">
        <form method="POST" action="{{ route('admin.peminjaman.serahkan', $peminjaman) }}">@csrf
            <button class="btn btn-sp"><i class="bi bi-box-arrow-up-right me-1"></i> Serahkan Alat (ubah status menjadi Dipinjam)</button>
        </form>
    </div>
@elseif (in_array($peminjaman->status, ['Dipinjam', 'Terlambat']))
    <div class="card-sp card-pad mt-3">
        <a href="{{ route('admin.pengembalian.form', $peminjaman) }}" class="btn btn-sp"><i class="bi bi-journal-arrow-down me-1"></i> Proses Pengembalian</a>
    </div>
@endif
@endsection
