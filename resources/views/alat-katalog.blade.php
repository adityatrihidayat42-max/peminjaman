@extends('layouts.public')

@section('title', 'Daftar Alat')

@section('content')
<section class="py-5 sec-soft">
    <div class="container">
        <div class="sec-eyebrow">Katalog</div>
        <h1 class="sec-title">Daftar Alat Peminjaman</h1>
        <form method="GET" class="row g-2 my-3">
            <div class="col-md-5"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama alat..."></div>
            <div class="col-md-4">
                <select name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sp flex-fill">Cari</button>
                <a href="{{ route('alat.katalog') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-4">
            @forelse ($alats as $a)
                @include('partials.kartu-alat', ['a' => $a])
            @empty
                <div class="col-12"><div class="alert alert-info mb-0">Alat tidak ditemukan.</div></div>
            @endforelse
        </div>
        <div class="mt-4">{{ $alats->links() }}</div>
    </div>
</section>
@endsection
