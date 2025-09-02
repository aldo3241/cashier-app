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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('kd_produk');
            $table->string('kd_produk_jenis', 10);
            $table->integer('kd_pemasok')->nullable();
            $table->string('nama_produk', 255);
            $table->string('gambar_produk', 255)->nullable();
            $table->string('material', 255)->nullable();
            $table->string('spesifik', 255)->nullable();
            $table->string('ukuran', 100)->nullable();
            $table->string('satuan', 50)->nullable();
            $table->integer('berat')->nullable();
            $table->integer('stok_total')->default(0);
            $table->decimal('hpp', 10, 2)->default(0);
            $table->decimal('harga_jual', 10, 2);
            $table->decimal('prediksi_laba', 10, 2)->nullable();
            $table->string('pemasok', 255)->nullable();
            $table->string('dibuat_oleh', 100)->nullable();
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_updated')->nullable();
            $table->string('barcode', 100)->nullable();
            
            $table->index('kd_produk_jenis');
            $table->index('barcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
