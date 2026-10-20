{{-- $items: koleksi Peminjaman (user & details.alat dimuat), $aksi: closure/route name opsional --}}
<div class="table-responsive">
    <table class="table table-hover table-sp align-middle">
        <thead>
            <tr>
                <th>#</th><th>Peminjam</th><th>Alat</th><th>Tanggal Pinjam</th><th>Rencana Kembali</th><th>Status</th><th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($items as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->user->name }}</td>
                <td>{{ $p->ringkasan_alat }}</td>
                <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                <td>{{ $p->tanggal_rencana_kembali->format('d M Y') }}</td>
                <td><span class="sp-badge {{ \App\Models\Peminjaman::badge($p->status) }}">{{ $p->status }}</span></td>
                <td class="text-end"><a href="{{ route($rute, $p) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
