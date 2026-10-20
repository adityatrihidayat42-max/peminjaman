@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<h1 class="page-title mb-3">Edit Kategori</h1>
<div class="card-sp" style="max-width:520px"><div class="card-pad">
    <form method="POST" action="{{ route('admin.kategori.update', $kategori) }}">
        @csrf @method('PUT')
        <label class="form-label">Nama Kategori</label>
        <input name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" class="form-control @error('nama_kategori') is-invalid @enderror">
        @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-sp">Simpan</button>
            <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
