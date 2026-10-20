@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
<h1 class="page-title">Pengembalian</h1>
<p class="page-sub mb-3">Alat yang belum dikembalikan.</p>

<div class="card-sp">
    <div class="table-responsive">
        <table class="table table-hover table-sp">
            <thead><tr><th>#</th><th>Peminjam</th><th>Alat</th><th>Tanggal Pinjam</th><th>Rencana Kembali</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($items as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ $p->ringkasan_alat }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                    <td class="{{ $p->terlambat ? 'text-danger fw-semibold' : '' }}">{{ $p->tanggal_rencana_kembali->format('d M Y') }}</td>
                    <td><span class="sp-badge {{ \App\Models\Peminjaman::badge($p->status) }}">{{ $p->status }}</span></td>
                    <td class="text-end"><a href="{{ route('admin.pengembalian.form', $p) }}" class="btn btn-sm btn-sp">Proses Pengembalian</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-secondary py-4">Tidak ada alat yang sedang dipinjam.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-pad">{{ $items->links() }}</div>
</div>
@endsection
