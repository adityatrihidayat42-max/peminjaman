@extends('layouts.app')

@section('title', 'Data Alat')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div><h1 class="page-title">Data Alat</h1><p class="page-sub">Kelola inventaris alat praktik RPL.</p></div>
    <a href="{{ route('admin.alat.create') }}" class="btn btn-sp"><i class="bi bi-plus-lg me-1"></i> Tambah Alat</a>
</div>

<div class="card-sp">
    <div class="card-pad border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-4"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari kode / nama alat..."></div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach ($kategoris as $k)<option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama_kategori }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="kondisi" class="form-select">
                    <option value="">Semua kondisi</option>
                    @foreach ($kondisis as $k)<option @selected(request('kondisi') == $k)>{{ $k }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2"><button class="btn btn-sp flex-fill">Filter</button><a href="{{ route('admin.alat.index') }}" class="btn btn-outline-secondary">Reset</a></div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sp">
            <thead><tr><th>Kode</th><th>Nama Alat</th><th>Kategori</th><th class="text-end">Total</th><th class="text-end">Tersedia</th><th>Kondisi</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($alats as $a)
                <tr>
                    <td><strong>{{ $a->kode_alat }}</strong></td>
                    <td>{{ $a->nama_alat }}</td>
                    <td>{{ $a->kategori->nama_kategori }}</td>
                    <td class="text-end">{{ $a->jumlah_total }}</td>
                    <td class="text-end"><span class="{{ $a->tersedia > 0 ? 'text-success' : 'text-danger' }} fw-semibold">{{ $a->tersedia }}</span></td>
                    <td><span class="sp-badge {{ $a->kondisi === 'Baik' ? 'sp-badge-success' : ($a->kondisi === 'Rusak Ringan' ? 'sp-badge-warning' : 'sp-badge-danger') }}">{{ $a->kondisi }}</span></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.alat.show', $a) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                        <a href="{{ route('admin.alat.edit', $a) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('admin.alat.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Hapus alat {{ $a->nama_alat }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-secondary py-4">Alat tidak ditemukan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-pad">{{ $alats->links() }}</div>
</div>
@endsection
