@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $peminjaman->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="page-title">Transaksi #{{ $peminjaman->id }}</h1>
    <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>
@include('partials.detail-transaksi')
@endsection
