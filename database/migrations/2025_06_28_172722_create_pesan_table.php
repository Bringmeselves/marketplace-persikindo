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
        Schema::create('pesan', function (Blueprint $table) {
        $table->id();
        $table->foreignId('chat_id')->constrained('chat')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->text('isi_pesan')->nullable(); // Boleh kosong jika hanya file
        $table->string('file_path')->nullable(); // Menyimpan path file
        $table->string('file_type')->nullable(); // Seperti 'image/png', 'application/pdf'
        $table->string('file_name')->nullable(); // Nama asli file (jika ingin disimpan)
        $table->boolean('sudah_dibaca')->default(false);
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan');
    }
};
