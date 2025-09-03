<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to produk table for faster searches
        Schema::table('produk', function (Blueprint $table) {
            // Check if indexes exist before creating them
            $indexes = $this->getTableIndexes('produk');
            
            if (!in_array('produk_nama_produk_index', $indexes)) {
                $table->index('nama_produk');
            }
            if (!in_array('produk_barcode_index', $indexes)) {
                $table->index('barcode');
            }
            if (!in_array('produk_kd_produk_jenis_index', $indexes)) {
                $table->index('kd_produk_jenis');
            }
            if (!in_array('produk_stok_total_index', $indexes)) {
                $table->index('stok_total');
            }
        });

        // Add indexes to stok table for faster stock calculations
        Schema::table('stok', function (Blueprint $table) {
            $indexes = $this->getTableIndexes('stok');
            
            if (!in_array('stok_kd_produk_index', $indexes)) {
                $table->index('kd_produk');
            }
            if (!in_array('stok_date_created_index', $indexes)) {
                $table->index('date_created');
            }
            if (!in_array('stok_kd_produk_date_created_index', $indexes)) {
                $table->index(['kd_produk', 'date_created']);
            }
        });

        // Add indexes to penjualan table for faster sales queries
        Schema::table('penjualan', function (Blueprint $table) {
            $indexes = $this->getTableIndexes('penjualan');
            
            if (!in_array('penjualan_date_created_index', $indexes)) {
                $table->index('date_created');
            }
            if (!in_array('penjualan_status_bayar_index', $indexes)) {
                $table->index('status_bayar');
            }
            if (!in_array('penjualan_kd_pelanggan_index', $indexes)) {
                $table->index('kd_pelanggan');
            }
            if (!in_array('penjualan_date_created_status_bayar_index', $indexes)) {
                $table->index(['date_created', 'status_bayar']);
            }
        });

        // Add indexes to penjualan_detail table
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $indexes = $this->getTableIndexes('penjualan_detail');
            
            if (!in_array('penjualan_detail_kd_penjualan_index', $indexes)) {
                $table->index('kd_penjualan');
            }
            if (!in_array('penjualan_detail_kd_produk_index', $indexes)) {
                $table->index('kd_produk');
            }
            if (!in_array('penjualan_detail_date_created_index', $indexes)) {
                $table->index('date_created');
            }
        });

        // Add indexes to metode_pembayaran table
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $indexes = $this->getTableIndexes('metode_pembayaran');
            
            if (!in_array('metode_pembayaran_nama_metode_pembayaran_index', $indexes)) {
                $table->index('nama_metode_pembayaran');
            }
            if (!in_array('metode_pembayaran_is_active_index', $indexes)) {
                $table->index('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from produk table
        Schema::table('produk', function (Blueprint $table) {
            $table->dropIndex(['nama_produk']);
            $table->dropIndex(['barcode']);
            $table->dropIndex(['kd_produk_jenis']);
            $table->dropIndex(['stok_total']);
        });

        // Remove indexes from stok table
        Schema::table('stok', function (Blueprint $table) {
            $table->dropIndex(['kd_produk']);
            $table->dropIndex(['date_created']);
            $table->dropIndex(['kd_produk', 'date_created']);
        });

        // Remove indexes from penjualan table
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropIndex(['date_created']);
            $table->dropIndex(['status_bayar']);
            $table->dropIndex(['kd_pelanggan']);
            $table->dropIndex(['date_created', 'status_bayar']);
        });

        // Remove indexes from penjualan_detail table
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->dropIndex(['kd_penjualan']);
            $table->dropIndex(['kd_produk']);
            $table->dropIndex(['date_created']);
        });

        // Remove indexes from metode_pembayaran table
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->dropIndex(['nama_metode_pembayaran']);
            $table->dropIndex(['is_active']);
        });
    }

    /**
     * Get existing indexes for a table
     */
    private function getTableIndexes($tableName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$tableName}");
        return array_map(function($index) {
            return $index->Key_name;
        }, $indexes);
    }
};
