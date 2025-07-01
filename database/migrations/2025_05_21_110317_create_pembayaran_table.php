<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('checkout_id')->constrained('checkout')->onDelete('cascade');
            $table->string('order_id')->nullable();     // ID unik transaksi Midtrans
            $table->string('snap_token')->nullable();
            $table->string('metode_pembayaran'); // Contoh: Transfer Bank, COD
            $table->integer('total_bayar');
            $table->enum('status_pembayaran', ['pending', 'lunas'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
