@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
{{-- HERO --}}
<section class="hero">
    <div class="container py-5">
        <div class="row align-items-center py-lg-4">
            <div class="col-lg-8">
                <div class="hero-eyebrow">Sistem Peminjaman Alat</div>
                <h1>Jurusan Rekayasa Perangkat Lunak <span>(RPL)</span></h1>
                <p class="lead-sm">Kelola peminjaman alat dengan mudah, cepat, dan terstruktur. Dukung kegiatan praktik dan pengembangan keterampilan di jurusan RPL.</p>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="{{ auth()->check() && auth()->user()->isSiswa() ? route('siswa.peminjaman.create') : route('login') }}" class="btn btn-sp">
                        Pinjam Alat Sekarang <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="{{ route('alat.katalog') }}" class="btn btn-sp-outline">Lihat Daftar Alat</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- KEUNGGULAN --}}
<section class="py-4 bg-white">
    <div class="container">
        <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-5">
            @foreach ([
                ['bi-gear', 'Mudah Digunakan', 'Proses peminjaman alat lebih cepat dan praktis.'],
                ['bi-shield-check', 'Data Aman', 'Data peminjaman dan pengguna dijamin aman.'],
                ['bi-clock', 'Tepat Waktu', 'Pantau jadwal peminjaman secara real-time.'],
                ['bi-box-seam', 'Ragam Alat Lengkap', 'Tersedia berbagai alat untuk kebutuhan praktik.'],
                ['bi-people', 'Dukungan Admin', 'Bantuan dan monitoring dari tim pengelola.'],
            ] as [$ikon, $judul, $isi])
                <div class="col">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi {{ $ikon }}"></i></div>
                        <h6>{{ $judul }}</h6>
                        <p>{{ $isi }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- DAFTAR ALAT --}}
<section class="py-5 sec-soft">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-4">
            <div>
                <div class="sec-eyebrow">Alat Terpopuler</div>
                <h2 class="sec-title">Daftar Alat Peminjaman</h2>
                <p class="text-secondary mb-0">Berikut adalah beberapa alat yang paling sering dipinjam oleh siswa jurusan Rekayasa Perangkat Lunak.</p>
            </div>
            <a href="{{ route('alat.katalog') }}" class="fw-semibold small">Lihat Semua Alat <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-5">
            @forelse ($populer as $a)
                @include('partials.kartu-alat', ['a' => $a])
            @empty
                <div class="col-12"><div class="alert alert-info mb-0">Belum ada data alat.</div></div>
            @endforelse
        </div>
    </div>
</section>

{{-- PROSES --}}
<section class="proc py-5">
    <div class="container">
        <div class="sec-eyebrow">Proses Peminjaman</div>
        <h2 class="sec-title text-white mb-4">Cara Mudah Meminjam Alat</h2>
        <div class="row g-4">
            @foreach ([
                ['bi-file-earmark-text', '01', 'Ajukan Peminjaman', 'Pilih alat yang diinginkan dan isi form peminjaman.'],
                ['bi-check2-square', '02', 'Verifikasi Admin', 'Tim admin akan memverifikasi permintaan Anda.'],
                ['bi-box-seam', '03', 'Ambil Alat', 'Setelah disetujui, ambil alat sesuai jadwal yang ditentukan.'],
                ['bi-arrow-left-right', '04', 'Kembalikan Alat', 'Kembalikan alat tepat waktu agar dapat digunakan oleh orang lain.'],
            ] as $i => [$ikon, $no, $judul, $isi])
                <div class="col-md-6 col-lg-3">
                    <div class="step">
                        <div class="step-icon"><i class="bi {{ $ikon }}"></i></div>
                        <div>
                            <div class="step-no">{{ $no }}</div>
                            <h6>{{ $judul }}</h6>
                            <p>{{ $isi }}</p>
                        </div>
                        @if ($i < 3)<i class="bi bi-arrow-right step-arrow ms-auto"></i>@endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PENGUMUMAN --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <div class="sec-eyebrow">Informasi &amp; Berita</div>
                <h2 class="sec-title mb-0">Pengumuman Terbaru</h2>
            </div>
            <a href="{{ route('tentang') }}" class="fw-semibold small">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            @foreach ($pengumuman as $n)
                <div class="col-md-4">
                    <div class="news-card">
                        <div class="news-img"><i class="bi {{ $n['ikon'] }}"></i></div>
                        <div class="news-body">
                            <div class="news-date"><i class="bi bi-calendar3 me-1"></i>{{ $n['tanggal'] }}</div>
                            <h6>{{ $n['judul'] }}</h6>
                            <p>{{ $n['isi'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
