<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIPERAL') — Peminjaman Alat RPL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/siperal.css') }}" rel="stylesheet">
    <style>
        .app-side button.item { display: flex; align-items: center; gap: 8px; color: #fff; padding: 8px 10px; font-size: 14px; cursor: pointer; }
        .app-side button.item:hover { background: rgba(255, 255, 255, .12); }
    </style>
</head>
<body>
@php
    $me = auth()->user();
    $on = fn (string ...$pola) => request()->routeIs(...$pola);
@endphp

@auth
    <header class="app-top">
        <button type="button" class="btn btn-sm btn-outline-secondary d-lg-none" id="sideToggle" aria-label="Menu">
            <i class="bi bi-list"></i>
        </button>
        <a href="{{ route('dashboard') }}" class="app-brand"><i class="bi bi-pc-display"></i> SIPERAL</a>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="sp-badge sp-badge-muted text-uppercase">{{ $me->role }}</span>
            <strong class="small d-none d-sm-inline">{{ $me->name }}</strong>
        </div>
    </header>

    <aside class="app-side" id="appSide">
        <div class="menu-label">MENU</div>

        @if ($me->isAdmin())
            <a class="item {{ $on('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="item {{ $on('admin.alat.*') ? 'active' : '' }}" href="{{ route('admin.alat.index') }}"><i class="bi bi-boxes"></i> Data Alat</a>
            <a class="item {{ $on('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}"><i class="bi bi-tags"></i> Kategori</a>
            <a class="item {{ $on('admin.peminjaman.*') ? 'active' : '' }}" href="{{ route('admin.peminjaman.index') }}"><i class="bi bi-journal-text"></i> Peminjaman</a>
            <a class="item {{ $on('admin.pengembalian.*') ? 'active' : '' }}" href="{{ route('admin.pengembalian.index') }}"><i class="bi bi-arrow-return-left"></i> Pengembalian</a>
            <a class="item {{ $on('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
            <a class="item {{ $on('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Akun</a>
        @elseif ($me->isSiswa())
            <a class="item {{ $on('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="item {{ $on('siswa.peminjaman.create') ? 'active' : '' }}" href="{{ route('siswa.peminjaman.create') }}"><i class="bi bi-plus-circle"></i> Ajukan Peminjaman</a>
            <a class="item {{ $on('siswa.peminjaman.index', 'siswa.peminjaman.show') && request('tab') !== 'riwayat' ? 'active' : '' }}" href="{{ route('siswa.peminjaman.index') }}"><i class="bi bi-journal-text"></i> Peminjaman Saya</a>
            <a class="item {{ $on('siswa.peminjaman.index') && request('tab') === 'riwayat' ? 'active' : '' }}" href="{{ route('siswa.peminjaman.index', ['tab' => 'riwayat']) }}"><i class="bi bi-clock-history"></i> Riwayat</a>
        @else
            <a class="item {{ $on('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
        @endif

        <div class="menu-label mt-3">LAINNYA</div>
        <a class="item" href="{{ route('beranda') }}"><i class="bi bi-house"></i> Beranda</a>
        <a class="item" href="{{ route('alat.katalog') }}"><i class="bi bi-grid"></i> Katalog Alat</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="item logout-btn"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </aside>

    <main class="app-main">
        @include('partials.flash')
        @yield('content')
    </main>
@else
    <main class="container py-4">
        @include('partials.flash')
        @yield('content')
    </main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var tombol = document.getElementById('sideToggle');
    if (tombol) {
        tombol.addEventListener('click', function () {
            document.getElementById('appSide').classList.toggle('open');
        });
    }
</script>
</body>
</html>
