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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('kd_penjualan');
            $table->string('no_faktur_penjualan', 50)->unique();
            $table->unsignedBigInteger('kd_pelanggan')->nullable();
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('pajak', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->decimal('lebih_bayar', 15, 2)->default(0);
            $table->enum('status_bayar', ['Lunas', 'Pending', 'Batal'])->default('Pending');
            $table->string('keuangan_kotak', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status_barang', ['diterima langsung', 'pending', 'dikirim'])->default('pending');
            $table->string('dibuat_oleh', 100)->nullable();
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_updated')->nullable();
            
            $table->index('kd_pelanggan');
            $table->index('status_bayar');
            $table->index('date_created');
            $table->index(['date_created', 'status_bayar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
