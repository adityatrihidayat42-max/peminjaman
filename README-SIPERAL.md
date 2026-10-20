# SIPERAL — Sistem Peminjaman Alat RPL (Laravel)

Paket ini berisi **kode aplikasi SIPERAL** (migrasi, model, controller, route, view Blade, seeder, tes, CSS) yang ditumpuk di atas proyek Laravel 11/12 yang baru dibuat.

## Pasang (± 5 menit)

Prasyarat: PHP ≥ 8.2, Composer.

```bash
composer create-project laravel/laravel siperal
cd siperal

# salin isi paket ini ke dalam folder siperal (timpa jika diminta)
#   app/  database/  public/  resources/  routes/  tests/

# pakai SQLite (paling mudah) -> di .env:
#   DB_CONNECTION=sqlite     (hapus/komentari DB_HOST, DB_DATABASE, dst.)
# atau MySQL sesuai PRD -> isi DB_* di .env, buat database `siperal`

php artisan migrate --seed
php artisan serve
```

Buka http://127.0.0.1:8000

> Catatan: `routes/web.php` dan `app/Models/User.php` bawaan Laravel **ditimpa** oleh paket ini.

## Akun demo (password: `password`)

| Role  | Email               |
|-------|---------------------|
| Admin | admin@siperal.test  |
| Siswa | siswa@siperal.test  |
| Siswa | siswa2@siperal.test |
| Guru  | guru@siperal.test   |

## Menjalankan tes

```bash
php artisan test
```

## Aturan penting

- **Stok** tidak disimpan ganda: `tersedia = jumlah_total − unit pada peminjaman berstatus Disetujui/Dipinjam/Terlambat`. Persetujuan memakai transaksi DB + `lockForUpdate` agar tidak ada dobel pesan.
- Alur status: `Menunggu → Disetujui → Dipinjam → Dikembalikan` (atau `Ditolak`; `Terlambat` otomatis jika lewat tanggal rencana).
- Pengembalian dengan kondisi *Rusak Berat* / *Hilang* mengurangi `jumlah_total` alat.
- Alat/kategori/akun yang punya relasi/riwayat tidak bisa dihapus; transaksi tidak pernah dihapus.
- Role dicek server-side lewat `RoleMiddleware` (siswa → 403 di URL admin). Guru hanya bisa melihat laporan.
- Pengumuman di beranda bersifat statis (`HomeController@beranda`); PRD belum mendefinisikan tabelnya.
- Ikon alat memakai Bootstrap Icons sebagai pengganti foto produk pada desain.
- Bootstrap & Bootstrap Icons dimuat dari CDN (butuh internet saat menjalankan).
