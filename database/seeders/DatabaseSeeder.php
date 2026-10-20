<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo — semua berpassword "password"
        $admin = User::create(['name' => 'Admin Lab RPL', 'email' => 'admin@siperal.test', 'password' => 'password', 'role' => 'admin']);
        $siswa1 = User::create(['name' => 'Budi Santoso', 'email' => 'siswa@siperal.test', 'password' => 'password', 'role' => 'siswa']);
        $siswa2 = User::create(['name' => 'Siti Aminah', 'email' => 'siswa2@siperal.test', 'password' => 'password', 'role' => 'siswa']);
        User::create(['name' => 'Guru Pembimbing', 'email' => 'guru@siperal.test', 'password' => 'password', 'role' => 'guru']);

        $kat = collect(['Komputer & Laptop', 'Perangkat Monitor', 'Server & Jaringan', 'IoT & Embedded', 'Aksesori Komputer', 'Perangkat Presentasi'])
            ->mapWithKeys(fn ($n) => [$n => Kategori::create(['nama_kategori' => $n])->id]);

        // Total dipilih agar stok tersedia sama dengan contoh desain
        // (Laptop 5, Monitor 3, Server Mini 2, Switch 4, Arduino 6) setelah transaksi contoh.
        $alat = [
            ['RPL-001', 'Laptop Developer', 'Komputer & Laptop', 8, 'Rak A1'],
            ['RPL-002', 'Monitor 24 inch', 'Perangkat Monitor', 3, 'Rak A2'],
            ['RPL-003', 'Server Mini (Raspberry Pi)', 'Server & Jaringan', 2, 'Rak B1'],
            ['RPL-004', 'Switch Jaringan', 'Server & Jaringan', 4, 'Rak B2'],
            ['RPL-005', 'Arduino Uno', 'IoT & Embedded', 6, 'Rak C1'],
            ['RPL-006', 'Mouse USB', 'Aksesori Komputer', 20, 'Rak D1'],
            ['RPL-007', 'Kabel LAN', 'Server & Jaringan', 15, 'Rak B3'],
            ['RPL-008', 'Proyektor Portabel', 'Perangkat Presentasi', 2, 'Rak E1'],
        ];
        $model = [];
        foreach ($alat as [$kode, $nama, $k, $total, $lokasi]) {
            $model[$kode] = Alat::create([
                'kode_alat' => $kode, 'nama_alat' => $nama, 'kategori_id' => $kat[$k],
                'jumlah_total' => $total, 'kondisi' => 'Baik', 'lokasi' => $lokasi,
                'keterangan' => 'Alat praktik jurusan RPL.',
            ]);
        }

        $buat = function (User $u, string $status, array $items, string $pinjam, string $kembali, ?string $aktual = null) use ($admin) {
            $p = Peminjaman::create([
                'user_id' => $u->id, 'tanggal_pinjam' => $pinjam, 'tanggal_rencana_kembali' => $kembali,
                'tanggal_kembali_aktual' => $aktual, 'status' => $status, 'catatan' => 'Untuk praktik.',
            ]);
            foreach ($items as [$alatModel, $jml]) {
                $p->details()->create([
                    'alat_id' => $alatModel->id, 'jumlah' => $jml,
                    'kondisi_saat_kembali' => $status === 'Dikembalikan' ? 'Baik' : null,
                ]);
            }
            $p->riwayats()->create(['user_id' => $u->id, 'status' => 'Menunggu', 'catatan' => 'Pengajuan dibuat.']);
            if ($status !== 'Menunggu') {
                $p->riwayats()->create(['user_id' => $admin->id, 'status' => $status]);
            }
        };

        $buat($siswa1, 'Dipinjam', [[$model['RPL-001'], 2]], now()->subDays(1)->toDateString(), now()->addDays(3)->toDateString());
        $buat($siswa2, 'Disetujui', [[$model['RPL-001'], 1]], now()->addDay()->toDateString(), now()->addDays(4)->toDateString());
        $buat($siswa2, 'Menunggu', [[$model['RPL-005'], 1]], now()->addDays(2)->toDateString(), now()->addDays(5)->toDateString());
        $buat($siswa1, 'Dikembalikan', [[$model['RPL-004'], 1]], now()->subDays(10)->toDateString(), now()->subDays(7)->toDateString(), now()->subDays(7)->toDateString());
    }
}
