{{-- $peminjaman dengan user, details.alat, riwayats.user --}}
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card-sp mb-3">
            <div class="card-head"><h6>Informasi Peminjaman</h6><span class="sp-badge {{ \App\Models\Peminjaman::badge($peminjaman->status) }}">{{ $peminjaman->status }}</span></div>
            <div class="card-pad">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Peminjam</dt><dd class="col-sm-8">{{ $peminjaman->user->name }} <span class="text-secondary">({{ $peminjaman->user->email }})</span></dd>
                    <dt class="col-sm-4">Tanggal pinjam</dt><dd class="col-sm-8">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</dd>
                    <dt class="col-sm-4">Rencana kembali</dt><dd class="col-sm-8">{{ $peminjaman->tanggal_rencana_kembali->format('d M Y') }}</dd>
                    <dt class="col-sm-4">Kembali aktual</dt>
                    <dd class="col-sm-8">
                        {{ $peminjaman->tanggal_kembali_aktual?->format('d M Y') ?? '-' }}
                        @if ($peminjaman->terlambat)<span class="sp-badge sp-badge-danger ms-1">Terlambat</span>@endif
                    </dd>
                    <dt class="col-sm-4">Catatan</dt><dd class="col-sm-8">{{ $peminjaman->catatan ?: '-' }}</dd>
                </dl>
            </div>
        </div>
        <div class="card-sp">
            <div class="card-head"><h6>Alat yang Dipinjam</h6></div>
            <div class="table-responsive">
                <table class="table table-sp">
                    <thead><tr><th>Kode</th><th>Nama Alat</th><th class="text-end">Jumlah</th><th>Kondisi Saat Kembali</th></tr></thead>
                    <tbody>
                    @foreach ($peminjaman->details as $d)
                        <tr>
                            <td>{{ $d->alat->kode_alat }}</td><td>{{ $d->alat->nama_alat }}</td>
                            <td class="text-end">{{ $d->jumlah }}</td><td>{{ $d->kondisi_saat_kembali ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-sp">
            <div class="card-head"><h6>Riwayat Status</h6></div>
            <div class="card-pad">@include('partials.riwayat-status')</div>
        </div>
    </div>
</div>
