<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('checkout_id')->constrained('checkout')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('toko')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('nomor_wa')->nullable();
            $table->string('alamat_penerima');
            $table->string('cities')->nullable();
            $table->string('kode_pos')->nullable();
            $table->text('catatan')->nullable();
            $table->string('kurir')->nullable();
            $table->string('layanan')->nullable();
            $table->integer('ongkir')->nullable();
            $table->string('status_pengiriman')->default('belum_dikirim');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengiriman');
    }
};
