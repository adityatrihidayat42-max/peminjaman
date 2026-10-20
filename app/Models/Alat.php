<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Alat extends Model
{
    public const KONDISI = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

    protected $fillable = [
        'kategori_id', 'kode_alat', 'nama_alat', 'jumlah_total',
        'kondisi', 'lokasi', 'keterangan',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    /**
     * ATURAN STOK (satu sumber kebenaran):
     * tersedia = jumlah_total - unit pada peminjaman berstatus aktif
     * (Disetujui, Dipinjam, Terlambat). Stok tidak disimpan di dua tempat,
     * jadi tidak mungkin tidak sinkron. Hasil tidak pernah negatif.
     */
    public function scopeDenganDipinjam(Builder $query): Builder
    {
        return $query->withSum(['details as jumlah_dipinjam' => function ($d) {
            $d->whereHas('peminjaman', fn ($p) => $p->whereIn('status', Peminjaman::STATUS_AKTIF));
        }], 'jumlah');
    }

    public function hitungDipinjam(): int
    {
        return (int) $this->details()
            ->whereHas('peminjaman', fn ($p) => $p->whereIn('status', Peminjaman::STATUS_AKTIF))
            ->sum('jumlah');
    }

    public function getDipinjamAttribute(): int
    {
        return array_key_exists('jumlah_dipinjam', $this->attributes)
            ? (int) $this->attributes['jumlah_dipinjam']
            : $this->hitungDipinjam();
    }

    public function getTersediaAttribute(): int
    {
        return max(0, $this->jumlah_total - $this->dipinjam);
    }

    /** Ikon Bootstrap Icons berdasarkan nama alat. */
    public function getIkonAttribute(): string
    {
        $nama = Str::lower($this->nama_alat);

        return match (true) {
            Str::contains($nama, 'laptop') => 'bi-laptop',
            Str::contains($nama, 'monitor') => 'bi-display',
            Str::contains($nama, ['server', 'raspberry']) => 'bi-hdd-rack',
            Str::contains($nama, ['switch', 'lan', 'router']) => 'bi-router',
            Str::contains($nama, 'arduino') => 'bi-cpu',
            Str::contains($nama, 'mouse') => 'bi-mouse',
            Str::contains($nama, 'proyektor') => 'bi-projector',
            Str::contains($nama, 'keyboard') => 'bi-keyboard',
            default => 'bi-box-seam',
        };
    }
}
