<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkout_item', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
             $table->foreignId('checkout_id')->constrained('checkout')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('varian_id')->constrained('varian')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('toko')->onDelete('cascade');

            $table->integer('jumlah');
            $table->integer('harga_satuan')->nullable();
            $table->string('gambar')->nullable(); // gambar varian

            $table->integer('total_harga');
            $table->enum('status', ['pending', 'dibayar', 'dibatalkan', 'menunggu_pembayaran'])->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_item');
    }
};
