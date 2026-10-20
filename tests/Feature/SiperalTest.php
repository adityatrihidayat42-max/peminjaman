<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiperalTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function siswa(): User
    {
        return User::factory()->create(['role' => 'siswa']);
    }

    private function alat(int $total = 5): Alat
    {
        $k = Kategori::create(['nama_kategori' => 'Komputer']);

        return Alat::create([
            'kategori_id' => $k->id, 'kode_alat' => 'RPL-' . uniqid(), 'nama_alat' => 'Laptop',
            'jumlah_total' => $total, 'kondisi' => 'Baik',
        ]);
    }

    private function ajukan(User $siswa, Alat $alat, int $jumlah)
    {
        return $this->actingAs($siswa)->post(route('siswa.peminjaman.store'), [
            'tanggal_pinjam' => today()->toDateString(),
            'tanggal_rencana_kembali' => today()->addDays(2)->toDateString(),
            'items' => [$alat->id => $jumlah],
        ]);
    }

    public function test_login_dan_logout(): void
    {
        $u = User::factory()->create(['email' => 'a@x.test', 'role' => 'admin']);
        $this->post(route('login.proses'), ['email' => 'a@x.test', 'password' => 'password'])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($u);
        $this->post(route('logout'))->assertRedirect();
        $this->assertGuest();
    }

    public function test_siswa_tidak_bisa_membuka_halaman_admin(): void
    {
        $siswa = $this->siswa();
        $this->actingAs($siswa)->get(route('admin.alat.index'))->assertForbidden();
        $this->actingAs($siswa)->post(route('admin.alat.store'), [])->assertForbidden();
        $this->actingAs($siswa)->get(route('laporan.index'))->assertForbidden();
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('admin.alat.index'))->assertRedirect(route('login'));
    }

    public function test_admin_crud_alat_dan_kode_unik(): void
    {
        $admin = $this->admin();
        $k = Kategori::create(['nama_kategori' => 'Jaringan']);
        $data = ['kategori_id' => $k->id, 'kode_alat' => 'RPL-100', 'nama_alat' => 'Switch', 'jumlah_total' => 3, 'kondisi' => 'Baik'];

        $this->actingAs($admin)->post(route('admin.alat.store'), $data)->assertRedirect(route('admin.alat.index'));
        $this->assertDatabaseHas('alats', ['kode_alat' => 'RPL-100']);

        $this->actingAs($admin)->post(route('admin.alat.store'), $data)->assertSessionHasErrors('kode_alat');
        $this->actingAs($admin)->post(route('admin.alat.store'), $data + ['jumlah_total' => -1, 'kode_alat' => 'X'])->assertSessionHasErrors('jumlah_total');
    }

    public function test_pengajuan_tidak_boleh_melebihi_stok(): void
    {
        $alat = $this->alat(2);
        $this->ajukan($this->siswa(), $alat, 5)->assertSessionHasErrors('items');
        $this->assertDatabaseCount('peminjamans', 0);
    }

    public function test_alur_penuh_stok_konsisten(): void
    {
        $admin = $this->admin();
        $siswa = $this->siswa();
        $alat = $this->alat(5);

        $this->ajukan($siswa, $alat, 3)->assertRedirect(route('siswa.peminjaman.index'));
        $p = Peminjaman::firstOrFail();
        $this->assertSame('Menunggu', $p->status);
        $this->assertSame(5, $alat->fresh()->tersedia); // belum memesan stok

        $this->actingAs($admin)->post(route('admin.peminjaman.setujui', $p))->assertSessionHas('sukses');
        $this->assertSame(2, $alat->fresh()->tersedia);

        $this->actingAs($admin)->post(route('admin.peminjaman.serahkan', $p));
        $this->assertSame('Dipinjam', $p->fresh()->status);

        $this->actingAs($admin)->post(route('admin.pengembalian.proses', $p), [
            'tanggal_kembali_aktual' => today()->toDateString(),
            'kondisi' => [$p->details()->first()->id => 'Baik'],
        ])->assertRedirect(route('admin.pengembalian.index'));

        $this->assertSame('Dikembalikan', $p->fresh()->status);
        $this->assertSame(5, $alat->fresh()->tersedia);
    }

    public function test_persetujuan_ditolak_jika_stok_sudah_habis(): void
    {
        $admin = $this->admin();
        $alat = $this->alat(2);
        $this->ajukan($this->siswa(), $alat, 2);
        $this->ajukan($this->siswa(), $alat, 2);
        [$p1, $p2] = Peminjaman::orderBy('id')->get()->all();

        $this->actingAs($admin)->post(route('admin.peminjaman.setujui', $p1))->assertSessionHas('sukses');
        $this->actingAs($admin)->post(route('admin.peminjaman.setujui', $p2))->assertSessionHas('gagal');

        $this->assertSame('Menunggu', $p2->fresh()->status);
        $this->assertSame(0, $alat->fresh()->tersedia); // tidak pernah negatif
    }

    public function test_siswa_tidak_bisa_melihat_peminjaman_siswa_lain(): void
    {
        $alat = $this->alat();
        $a = $this->siswa();
        $b = $this->siswa();
        $this->ajukan($a, $alat, 1);
        $p = Peminjaman::firstOrFail();

        $this->actingAs($b)->get(route('siswa.peminjaman.show', $p))->assertForbidden();
        $this->actingAs($a)->get(route('siswa.peminjaman.show', $p))->assertOk();
    }

    public function test_alat_dengan_riwayat_dan_kategori_terpakai_tidak_bisa_dihapus(): void
    {
        $admin = $this->admin();
        $alat = $this->alat();
        $this->ajukan($this->siswa(), $alat, 1);

        $this->actingAs($admin)->delete(route('admin.alat.destroy', $alat))->assertSessionHas('gagal');
        $this->assertDatabaseHas('alats', ['id' => $alat->id]);

        $this->actingAs($admin)->delete(route('admin.kategori.destroy', $alat->kategori))->assertSessionHas('gagal');
        $this->assertDatabaseHas('kategoris', ['id' => $alat->kategori_id]);
    }

    public function test_peminjaman_lewat_tempo_ditandai_terlambat(): void
    {
        $siswa = $this->siswa();
        $p = Peminjaman::create([
            'user_id' => $siswa->id, 'tanggal_pinjam' => today()->subDays(5), 'tanggal_rencana_kembali' => today()->subDay(), 'status' => 'Dipinjam',
        ]);
        Peminjaman::tandaiTerlambat();
        $this->assertSame('Terlambat', $p->fresh()->status);
    }

    public function test_laporan_dapat_difilter_dan_dicetak(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->get(route('laporan.index', ['status' => 'Menunggu']))->assertOk();
        $this->actingAs($admin)->get(route('laporan.cetak'))->assertOk();
    }

    public function test_halaman_publik(): void
    {
        $this->get(route('beranda'))->assertOk()->assertSee('Cara Mudah Meminjam Alat');
        $this->get(route('alat.katalog'))->assertOk();
        $this->get(route('login'))->assertOk();
    }
}
