<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->restrictOnDelete();
            $table->string('kode_alat', 30)->unique();
            $table->string('nama_alat');
            $table->unsignedInteger('jumlah_total')->default(0);
            $table->string('kondisi', 30)->default('Baik');
            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};
