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
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('produk_id')->nullable();
            $table->unsignedBigInteger('jasa_id')->nullable();
            $table->integer('qty');
            $table->decimal('harga', 12, 2);
            $table->timestamps();
            $table->softDeletes();
    
            $table->foreign('transaksi_id')->references('id')->on('transaksis');
            $table->foreign('produk_id')->references('id')->on('produks');
            $table->foreign('jasa_id')->references('id')->on('jasas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
