<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman — SIPERAL</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; margin: 24px; }
        h1 { font-size: 18px; margin: 0; } .sub { color: #555; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #999; padding: 5px 7px; text-align: left; }
        th { background: #eee; } .btn { margin-bottom: 12px; padding: 6px 14px; }
        @media print { .btn { display: none; } }
    </style>
</head>
<body>
    <button class="btn" onclick="window.print()">Cetak</button>
    <h1>Laporan Peminjaman Alat — Jurusan RPL</h1>
    <div class="sub">
        Dicetak: {{ now()->format('d M Y H:i') }}
        @if (! empty($filter['dari']) || ! empty($filter['sampai']))
            · Periode: {{ $filter['dari'] ?? '...' }} s/d {{ $filter['sampai'] ?? '...' }}
        @endif
        @if (! empty($filter['status'])) · Status: {{ $filter['status'] }} @endif
        @if (! empty($filter['peminjam'])) · Peminjam: {{ $filter['peminjam'] }} @endif
    </div>
    <table>
        <thead><tr><th>No</th><th>Peminjam</th><th>Alat</th><th>Tgl Pinjam</th><th>Rencana Kembali</th><th>Kembali Aktual</th><th>Status</th></tr></thead>
        <tbody>
        @forelse ($items as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td><td>{{ $p->user->name }}</td><td>{{ $p->ringkasan_alat }}</td>
                <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td><td>{{ $p->tanggal_rencana_kembali->format('d/m/Y') }}</td>
                <td>{{ $p->tanggal_kembali_aktual?->format('d/m/Y') ?? '-' }}</td><td>{{ $p->status }}</td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center">Tidak ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
    <p>Total transaksi: {{ $items->count() }}</p>
</body>
</html>
