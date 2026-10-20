<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    public const STATUS = ['Menunggu', 'Disetujui', 'Ditolak', 'Dipinjam', 'Dikembalikan', 'Terlambat'];

    /** Status yang menahan stok. */
    public const STATUS_AKTIF = ['Disetujui', 'Dipinjam', 'Terlambat'];

    /** Status yang belum dikembalikan (muncul di halaman pengembalian). */
    public const STATUS_BELUM_KEMBALI = ['Dipinjam', 'Terlambat'];

    protected $fillable = [
        'user_id', 'tanggal_pinjam', 'tanggal_rencana_kembali',
        'tanggal_kembali_aktual', 'status', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_rencana_kembali' => 'date',
            'tanggal_kembali_aktual' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function riwayats(): HasMany
    {
        return $this->hasMany(PeminjamanRiwayat::class)->latest('id');
    }

    /** Ubah status sekaligus mencatat riwayat. */
    public function ubahStatus(string $status, ?int $oleh = null, ?string $catatan = null): void
    {
        $this->update(['status' => $status]);
        $this->riwayats()->create([
            'user_id' => $oleh,
            'status' => $status,
            'catatan' => $catatan,
        ]);
    }

    /** Tandai transaksi yang melewati tanggal rencana kembali sebagai Terlambat. */
    public static function tandaiTerlambat(): void
    {
        static::where('status', 'Dipinjam')
            ->whereDate('tanggal_rencana_kembali', '<', today())
            ->get()
            ->each(fn (self $p) => $p->ubahStatus('Terlambat', null, 'Melewati tanggal rencana pengembalian.'));
    }

    /** Apakah pengembalian terlambat (aktual atau sedang berjalan). */
    public function getTerlambatAttribute(): bool
    {
        if ($this->tanggal_kembali_aktual) {
            return $this->tanggal_kembali_aktual->gt($this->tanggal_rencana_kembali);
        }

        return in_array($this->status, self::STATUS_BELUM_KEMBALI, true)
            && today()->gt($this->tanggal_rencana_kembali);
    }

    public function getTotalUnitAttribute(): int
    {
        return (int) $this->details->sum('jumlah');
    }

    public function getRingkasanAlatAttribute(): string
    {
        return $this->details
            ->map(fn ($d) => ($d->alat->nama_alat ?? '-') . ' ×' . $d->jumlah)
            ->implode(', ');
    }

    /** Kelas badge Bootstrap/SIPERAL untuk status. */
    public static function badge(string $status): string
    {
        return match ($status) {
            'Menunggu' => 'sp-badge-warning',
            'Disetujui' => 'sp-badge-info',
            'Dipinjam' => 'sp-badge-primary',
            'Dikembalikan' => 'sp-badge-success',
            'Ditolak', 'Terlambat' => 'sp-badge-danger',
            default => 'sp-badge-muted',
        };
    }
}
