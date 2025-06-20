<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenilaianTable extends Migration
{
    public function up()
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id'); // relasi ke produk
            $table->unsignedBigInteger('user_id');   // relasi ke user
            $table->tinyInteger('rating');           // nilai rating (1–5)
            $table->text('ulasan')->nullable();      // teks ulasan opsional
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian');
    }
}
