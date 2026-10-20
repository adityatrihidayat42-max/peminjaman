@extends('layouts.app')

@section('title', 'Proses Pengembalian')

@section('content')
<h1 class="page-title mb-1">Proses Pengembalian #{{ $peminjaman->id }}</h1>
<p class="page-sub mb-3">Peminjam: {{ $peminjaman->user->name }} · Rencana kembali {{ $peminjaman->tanggal_rencana_kembali->format('d M Y') }}</p>

<form method="POST" action="{{ route('admin.pengembalian.proses', $peminjaman) }}">
    @csrf
    <div class="card-sp mb-3">
        <div class="card-pad">
            <label class="form-label">Tanggal Pengembalian Aktual</label>
            <input type="date" name="tanggal_kembali_aktual" max="{{ today()->toDateString() }}" value="{{ old('tanggal_kembali_aktual', today()->toDateString()) }}" class="form-control @error('tanggal_kembali_aktual') is-invalid @enderror" style="max-width:240px">
            @error('tanggal_kembali_aktual')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="card-sp mb-3">
        <div class="card-head"><h6>Kondisi Alat Saat Kembali</h6></div>
        <div class="table-responsive">
            <table class="table table-sp">
                <thead><tr><th>Kode</th><th>Nama Alat</th><th class="text-end">Jumlah</th><th style="width:220px">Kondisi</th></tr></thead>
                <tbody>
                @foreach ($peminjaman->details as $d)
                    <tr>
                        <td>{{ $d->alat->kode_alat }}</td><td>{{ $d->alat->nama_alat }}</td><td class="text-end">{{ $d->jumlah }}</td>
                        <td>
                            <select name="kondisi[{{ $d->id }}]" class="form-select form-select-sm">
                                @foreach ($kondisis as $k)<option @selected(old("kondisi.{$d->id}", 'Baik') === $k)>{{ $k }}</option>@endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-pad pt-0 small text-secondary">
            Unit berkondisi <strong>Rusak Berat</strong> atau <strong>Hilang</strong> dikurangkan dari jumlah total inventaris; unit lainnya kembali ke stok tersedia.
        </div>
    </div>

    <div class="card-sp mb-3"><div class="card-pad">
        <label class="form-label">Catatan (opsional)</label>
        <textarea name="catatan" rows="2" class="form-control">{{ old('catatan') }}</textarea>
    </div></div>

    <button class="btn btn-sp">Simpan Pengembalian</button>
    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-outline-secondary">Batal</a>
</form>
@endsection
