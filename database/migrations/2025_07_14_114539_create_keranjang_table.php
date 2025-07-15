<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeranjangTable extends Migration
{
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('toko')->onDelete('cascade');
            $table->foreignId('varian_id')->nullable()->constrained('varian')->onDelete('set null');
            $table->integer('jumlah');
            $table->integer('harga_satuan');
            $table->integer('total_harga');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
}
