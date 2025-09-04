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
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id('kd_penjualan_detail');
            $table->unsignedBigInteger('kd_penjualan');
            $table->unsignedBigInteger('kd_produk');
            $table->string('nama_produk', 255);
            $table->string('produk_jenis', 100)->nullable();
            $table->unsignedBigInteger('kd_pemasok')->nullable();
            $table->string('pemasok', 100)->nullable();
            $table->string('sistem_bayar', 100)->nullable();
            $table->decimal('hpp', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('qty')->default(1);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('laba', 15, 2)->default(0);
            $table->enum('status_bayar', ['Lunas', 'Pending', 'Batal'])->default('Pending');
            $table->text('catatan')->nullable();
            $table->string('dibuat_oleh', 100)->nullable();
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_updated')->nullable();
            
            $table->foreign('kd_penjualan')->references('kd_penjualan')->on('penjualan')->onDelete('cascade');
            $table->foreign('kd_produk')->references('kd_produk')->on('produk')->onDelete('restrict');
            
            $table->index('kd_penjualan');
            $table->index('kd_produk');
            $table->index('date_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
    }
};
