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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // pembeli
            $table->foreignId('produk_id')->nullable()->constrained('produk')->onDelete('set null');
            $table->foreignId('jasa_id')->nullable()->constrained('jasa')->onDelete('set null');
            $table->date('tanggal');
            $table->enum('status', ['menunggu', 'dibayar', 'dikirim', 'selesai', 'batal'])->default('menunggu');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
