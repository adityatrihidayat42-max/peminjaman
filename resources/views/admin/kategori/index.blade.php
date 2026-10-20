@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<h1 class="page-title mb-3">Kategori Alat</h1>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card-sp">
            <div class="card-head"><h6>Tambah Kategori</h6></div>
            <div class="card-pad">
                <form method="POST" action="{{ route('admin.kategori.store') }}">
                    @csrf
                    <label class="form-label">Nama Kategori</label>
                    <input name="nama_kategori" value="{{ old('nama_kategori') }}" class="form-control mb-3 @error('nama_kategori') is-invalid @enderror" placeholder="Contoh: Perangkat jaringan">
                    @error('nama_kategori')<div class="invalid-feedback d-block mb-2">{{ $message }}</div>@enderror
                    <button class="btn btn-sp w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-sp">
            <div class="table-responsive">
                <table class="table table-hover table-sp">
                    <thead><tr><th>Nama Kategori</th><th class="text-end">Jumlah Alat</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    @forelse ($kategoris as $k)
                        <tr>
                            <td>{{ $k->nama_kategori }}</td>
                            <td class="text-end">{{ $k->alats_count }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.kategori.edit', $k) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.kategori.destroy', $k) }}" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" @disabled($k->alats_count > 0) title="{{ $k->alats_count > 0 ? 'Masih dipakai alat' : '' }}">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-4">Belum ada kategori.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
