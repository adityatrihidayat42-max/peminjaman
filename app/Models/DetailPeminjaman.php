<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjamans';

    public const KONDISI_KEMBALI = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang'];

    protected $fillable = ['peminjaman_id', 'alat_id', 'jumlah', 'kondisi_saat_kembali'];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class);
    }
}
