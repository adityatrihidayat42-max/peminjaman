@extends('layouts.app')

@section('title', $riwayat ? 'Riwayat Peminjaman' : 'Peminjaman Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h1 class="page-title">{{ $riwayat ? 'Riwayat Peminjaman' : 'Peminjaman Saya' }}</h1>
        <p class="page-sub">{{ $riwayat ? 'Peminjaman yang sudah selesai atau ditolak.' : 'Pengajuan dan peminjaman yang masih berjalan.' }}</p>
    </div>
    <a href="{{ route('siswa.peminjaman.create') }}" class="btn btn-sp"><i class="bi bi-plus-lg me-1"></i> Ajukan Peminjaman</a>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $riwayat ? '' : 'active' }}" href="{{ route('siswa.peminjaman.index') }}">Aktif</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $riwayat ? 'active' : '' }}" href="{{ route('siswa.peminjaman.index', ['tab' => 'riwayat']) }}">Riwayat</a>
    </li>
</ul>

<div class="card-sp">
    <div class="table-responsive">
        <table class="table table-hover table-sp">
            <thead>
                <tr><th>#</th><th>Alat</th><th>Tanggal Pinjam</th><th>Rencana Kembali</th><th>Status</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($items as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->ringkasan_alat }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                    <td class="{{ $p->terlambat ? 'text-danger fw-semibold' : '' }}">{{ $p->tanggal_rencana_kembali->format('d M Y') }}</td>
                    <td><span class="sp-badge {{ \App\Models\Peminjaman::badge($p->status) }}">{{ $p->status }}</span></td>
                    <td class="text-end"><a href="{{ route('siswa.peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">
                        {{ $riwayat ? 'Belum ada riwayat peminjaman.' : 'Belum ada peminjaman aktif.' }}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-pad">{{ $items->links() }}</div>
</div>
@endsection
