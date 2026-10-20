<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = ['admin', 'siswa', 'guru'];

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // password otomatis di-hash
        ];
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /** Tautan menu "Peminjaman" sesuai role (dipakai navbar). */
    public function urlPeminjaman(): string
    {
        return match ($this->role) {
            'admin' => route('admin.peminjaman.index'),
            'siswa' => route('siswa.peminjaman.index'),
            default => route('laporan.index'),
        };
    }

    /** Tautan menu "Riwayat" sesuai role. */
    public function urlRiwayat(): string
    {
        return match ($this->role) {
            'siswa' => route('siswa.peminjaman.index', ['tab' => 'riwayat']),
            default => route('laporan.index'),
        };
    }

    /** Tautan menu "Laporan" sesuai role. */
    public function urlLaporan(): string
    {
        return $this->isSiswa()
            ? route('siswa.peminjaman.index', ['tab' => 'riwayat'])
            : route('laporan.index');
    }
}
