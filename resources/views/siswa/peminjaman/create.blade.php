@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<h1 class="page-title">Ajukan Peminjaman</h1>
<p class="page-sub mb-3">Pilih alat dan jumlahnya, lalu isi tanggal peminjaman.</p>

<form method="POST" action="{{ route('siswa.peminjaman.store') }}" novalidate>
    @csrf
    <div class="card-sp mb-3">
        <div class="card-head"><h6>Pilih Alat</h6></div>
        <div class="table-responsive">
            <table class="table table-sp">
                <thead><tr><th>Kode</th><th>Nama Alat</th><th>Kategori</th><th class="text-end">Tersedia</th><th style="width:120px">Jumlah</th></tr></thead>
                <tbody>
                @forelse ($alats as $a)
                    <tr>
                        <td>{{ $a->kode_alat }}</td><td>{{ $a->nama_alat }}</td><td>{{ $a->kategori->nama_kategori }}</td>
                        <td class="text-end text-success fw-semibold">{{ $a->tersedia }}</td>
                        <td><input type="number" min="0" max="{{ $a->tersedia }}" name="items[{{ $a->id }}]" value="{{ old("items.{$a->id}", $dipilih === $a->id ? 1 : 0) }}" class="form-control form-control-sm qty-input"></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary py-4">Tidak ada alat yang tersedia saat ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-sp mb-3"><div class="card-pad">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" min="{{ today()->toDateString() }}" value="{{ old('tanggal_pinjam', today()->addDay()->toDateString()) }}" class="form-control @error('tanggal_pinjam') is-invalid @enderror">
                @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Rencana Pengembalian</label>
                <input type="date" name="tanggal_rencana_kembali" min="{{ today()->toDateString() }}" value="{{ old('tanggal_rencana_kembali', today()->addDays(3)->toDateString()) }}" class="form-control @error('tanggal_rencana_kembali') is-invalid @enderror">
                @error('tanggal_rencana_kembali')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Keperluan / Catatan</label>
                <textarea name="catatan" rows="2" class="form-control" placeholder="Contoh: praktik pemrograman web">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </div></div>

    <button class="btn btn-sp" @disabled($alats->isEmpty())><i class="bi bi-send me-1"></i> Kirim Pengajuan</button>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Batal</a>
</form>
@endsection
