@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
<div class="mb-3">
    <h1 class="page-title">Kelola Peminjaman</h1>
    <p class="page-sub">Setujui atau tolak pengajuan, serahkan alat, dan pantau status peminjaman.</p>
</div>

<div class="card-sp">
    <div class="card-pad border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sp flex-fill">Filter</button>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    @include('partials.tabel-transaksi', ['items' => $items, 'rute' => 'admin.peminjaman.show'])

    <div class="card-pad">{{ $items->links() }}</div>
</div>
@endsection
