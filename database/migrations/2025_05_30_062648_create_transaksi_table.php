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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('varian_id')->nullable()->constrained('varian')->onDelete('set null');
            $table->foreignId('checkout_id')->constrained('checkout')->onDelete('cascade');
            $table->foreignId('pengiriman_id')->constrained('pengiriman')->onDelete('cascade');
            $table->foreignId('pembayaran_id')->constrained('pembayaran')->onDelete('cascade');
            $table->string('resi')->nullable();
            $table->string('status')->default('diproses'); // diproses, dikirim, selesai, dibatalkan

            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};

