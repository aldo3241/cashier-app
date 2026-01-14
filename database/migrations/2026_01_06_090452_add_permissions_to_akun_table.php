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
        Schema::table('akun', function (Blueprint $table) {
            $table->boolean('menu_keuangan')->default(0);
            $table->boolean('edit_produk')->default(0);
            $table->boolean('edit_stok')->default(0);
            $table->boolean('penjualan')->default(0);
            $table->boolean('laporan')->default(0);
            $table->boolean('edit_akun')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akun', function (Blueprint $table) {
            $table->dropColumn([
                'menu_keuangan',
                'edit_produk',
                'edit_stok',
                'penjualan',
                'laporan',
                'edit_akun'
            ]);
        });
    }
};
