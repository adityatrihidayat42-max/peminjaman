<div class="col">
    <div class="tool-card">
        <div class="tool-thumb"><i class="bi {{ $a->ikon }}"></i></div>
        <div class="tool-body">
            <h6>{{ $a->nama_alat }}</h6>
            <div class="tool-cat">{{ $a->kategori->nama_kategori }}</div>
            <div class="tool-stock">
                <span class="dot {{ $a->tersedia > 0 && $a->kondisi === 'Baik' ? 'dot-green' : 'dot-red' }}"></span>
                Stok Tersedia: {{ $a->tersedia }}
            </div>
            @if ($a->tersedia > 0 && $a->kondisi === 'Baik')
                <a href="{{ auth()->check() ? (auth()->user()->isSiswa() ? route('siswa.peminjaman.create', ['alat' => $a->id]) : route('dashboard')) : route('login') }}" class="btn btn-sp w-100">
                    Pinjam Sekarang <i class="bi bi-arrow-right ms-1"></i>
                </a>
            @else
                <button class="btn btn-secondary w-100" disabled>Tidak Tersedia</button>
            @endif
        </div>
    </div>
</div>
