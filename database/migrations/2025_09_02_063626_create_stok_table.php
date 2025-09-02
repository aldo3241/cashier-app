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
        Schema::create('stok', function (Blueprint $table) {
            $table->id('kd_stok');
            $table->unsignedBigInteger('kd_produk');
            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);
            $table->string('klasifikasi', 100)->nullable();
            $table->string('no_ref', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->string('dibuat_oleh', 100)->nullable();
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_updated')->nullable();
            
            $table->index('kd_produk');
            $table->foreign('kd_produk')->references('kd_produk')->on('produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};
