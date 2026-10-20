<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanRiwayat extends Model
{
    protected $table = 'peminjaman_riwayats';

    protected $fillable = ['peminjaman_id', 'user_id', 'status', 'catatan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
