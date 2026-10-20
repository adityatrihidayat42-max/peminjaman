@extends('layouts.app')

@php($edit = $alat->exists)
@section('title', $edit ? 'Edit Alat' : 'Tambah Alat')

@section('content')
<h1 class="page-title mb-3">{{ $edit ? 'Edit Alat' : 'Tambah Alat' }}</h1>

<div class="card-sp"><div class="card-pad">
    <form method="POST" action="{{ $edit ? route('admin.alat.update', $alat) : route('admin.alat.store') }}" novalidate>
        @csrf
        @if ($edit) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Kode Inventaris</label>
                <input name="kode_alat" value="{{ old('kode_alat', $alat->kode_alat) }}" class="form-control @error('kode_alat') is-invalid @enderror" placeholder="RPL-009">
                @error('kode_alat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label class="form-label">Nama Alat</label>
                <input name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat) }}" class="form-control @error('nama_alat') is-invalid @enderror">
                @error('nama_alat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                    <option value="">Pilih kategori</option>
                    @foreach ($kategoris as $k)<option value="{{ $k->id }}" @selected(old('kategori_id', $alat->kategori_id) == $k->id)>{{ $k->nama_kategori }}</option>@endforeach
                </select>
                @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Jumlah Total</label>
                <input type="number" min="0" name="jumlah_total" value="{{ old('jumlah_total', $alat->jumlah_total) }}" class="form-control @error('jumlah_total') is-invalid @enderror">
                @error('jumlah_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
                    @foreach ($kondisis as $k)<option @selected(old('kondisi', $alat->kondisi) === $k)>{{ $k }}</option>@endforeach
                </select>
                @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Lokasi Penyimpanan</label>
                <input name="lokasi" value="{{ old('lokasi', $alat->lokasi) }}" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" rows="3" class="form-control">{{ old('keterangan', $alat->keterangan) }}</textarea>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-sp">Simpan</button>
            <a href="{{ route('admin.alat.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection
