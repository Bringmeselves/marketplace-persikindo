<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('nik');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('bukti_pendaftaran')->nullable();
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->timestamp('tanggal_disetujui')->nullable();

            // Tambahan untuk form pendaftaran
            $table->string('nama_perusahaan');
            $table->enum('legalitas', ['CV', 'PT']);
            $table->string('nib');
            $table->string('npwp');
            $table->string('sertifikat_halal')->nullable(); // opsional, file path
            $table->string('pirt')->nullable(); // opsional, file path
            $table->string('catatan')->nullable(); // untuk admin memberikan catatan jika ditolak

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
