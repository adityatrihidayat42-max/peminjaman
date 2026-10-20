@extends('layouts.public')

@section('title', 'Tentang')

@section('content')
<section class="py-5 sec-soft">
    <div class="container" style="max-width: 860px">
        <div class="sec-eyebrow">Tentang</div>
        <h1 class="sec-title">SIPERAL — Sistem Peminjaman Alat RPL</h1>
        <p class="text-secondary">Website inventaris dan peminjaman alat praktik Jurusan Rekayasa Perangkat Lunak. Pencatatan manual sering menyulitkan pengecekan ketersediaan alat, pelacakan peminjam, dan pencatatan pengembalian; SIPERAL menyimpan data inventaris dan transaksi dalam satu sistem.</p>

        <h5 class="fw-bold mt-4">Tujuan</h5>
        <ul class="text-secondary">
            <li>Memudahkan pengelolaan alat praktik RPL.</li>
            <li>Mencatat proses peminjaman dan pengembalian secara terstruktur.</li>
            <li>Menampilkan ketersediaan alat berdasarkan transaksi.</li>
            <li>Mengurangi kesalahan pencatatan.</li>
            <li>Menyediakan riwayat dan laporan yang mudah diperiksa.</li>
        </ul>

        <h5 class="fw-bold mt-4">Alur Peminjaman</h5>
        <ol class="text-secondary">
            <li>Login ke sistem.</li>
            <li>Siswa memilih alat lalu mengajukan peminjaman.</li>
            <li>Admin memeriksa dan menyetujui atau menolak.</li>
            <li>Alat diserahkan, status menjadi <em>Dipinjam</em>.</li>
            <li>Saat kembali, admin mencatat tanggal dan kondisi alat; stok diperbarui otomatis.</li>
        </ol>
    </div>
</section>
@endsection
