<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIPERAL') — Peminjaman Alat RPL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/siperal.css') }}" rel="stylesheet">
</head>
<body class="pub-body">
<nav class="navbar navbar-expand-lg pub-nav">
    <div class="container">
        @include('partials.logo')
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#pubMenu" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="pubMenu">
            @php($u = auth()->user())
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('alat.katalog') ? 'active' : '' }}" href="{{ route('alat.katalog') }}">Alat</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ $u ? $u->urlPeminjaman() : route('login') }}">Peminjaman</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ $u ? $u->urlRiwayat() : route('login') }}">Riwayat</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ $u ? $u->urlLaporan() : route('login') }}">Laporan</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang</a></li>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-sp btn-sm"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="btn btn-sp-outline btn-sm">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sp btn-sm"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@if (session('sukses') || session('gagal'))
    <div class="container mt-3">@include('partials.flash')</div>
@endif

@yield('content')

<footer class="pub-footer pt-4">
    <div class="container">
        <div class="row g-4 pb-4 align-items-start">
            <div class="col-lg-4">
                @include('partials.logo')
                <p class="mt-3 mb-0">Dukung inovasi dan kreativitas mahasiswa RPL dengan fasilitas alat yang memadai.</p>
            </div>
            <div class="col-6 col-lg-3 offset-lg-1">
                <h6>Menu</h6>
                <div class="d-grid gap-1">
                    <a href="{{ route('beranda') }}">Beranda</a>
                    <a href="{{ route('alat.katalog') }}">Alat</a>
                    <a href="{{ $u ? $u->urlPeminjaman() : route('login') }}">Peminjaman</a>
                    <a href="{{ $u ? $u->urlRiwayat() : route('login') }}">Riwayat</a>
                    <a href="{{ route('tentang') }}">Tentang</a>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <h6>Kontak</h6>
                <div class="d-grid gap-1">
                    <span><i class="bi bi-telephone text-primary me-2"></i>+62 812 3456 7890</span>
                    <span><i class="bi bi-envelope text-primary me-2"></i>rpl@sekolah.sch.id</span>
                    <span><i class="bi bi-geo-alt text-primary me-2"></i>Lab. RPL - Kampus</span>
                </div>
                <div class="social mt-3">
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="#" aria-label="GitHub"><i class="bi bi-github"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="copy py-3">
        <div class="container d-flex justify-content-between flex-wrap gap-2">
            <span>© {{ date('Y') }} Jurusan Rekayasa Perangkat Lunak. Semua hak dilindungi.</span>
            <span>Belajar • Berkarya • Berinovasi</span>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
