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
        Schema::create('registrasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('no_anggota')->nullable(); // opsional, bisa diisi setelah disetujui
            $table->string('nama_lengkap');
            $table->string('no_hp');
            $table->string('bukti_pendaftaran')->nullable(); // path bukti pendaftaran
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasi');
    }
};
