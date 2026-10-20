<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_rencana_kembali');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->string('status', 20)->default('Menunggu')->index();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->restrictOnDelete();
            $table->foreignId('alat_id')->constrained('alats')->restrictOnDelete();
            $table->unsignedInteger('jumlah');
            $table->string('kondisi_saat_kembali', 30)->nullable();
            $table->timestamps();
        });

        // Riwayat perubahan status (audit) — tidak pernah dihapus.
        Schema::create('peminjaman_riwayats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_riwayats');
        Schema::dropIfExists('detail_peminjamans');
        Schema::dropIfExists('peminjamans');
    }
};
