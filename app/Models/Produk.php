<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    
    protected $primaryKey = 'kd_produk';
    public $incrementing = false; // Jika kd_produk bukan auto increment
    protected $keyType = 'string'; // Jika kd_produk adalah string
    
    // Jika menggunakan custom timestamp columns
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $fillable = [
        'kd_produk',
        'kd_int',
        'kd_ext', 
        'kd_produk_jenis',
        'kd_pemasok',
        'nama_produk',
        'gambar_produk',
        'material',
        'spesifik',
        'ukuran',
        'satuan',
        'berat',
        'stok_masuk',
        'stok_keluar', 
        'stok_total',
        'hpp',
        'prediksi_laba',
        'harga_jual',
        'sistem_bayar',
        'dibuat_oleh',
        'pemasok',
        'jenis'
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'hpp' => 'decimal:2',
        'prediksi_laba' => 'decimal:2',
        'stok_masuk' => 'integer',
        'stok_keluar' => 'integer',
        'stok_total' => 'integer',
        'berat' => 'decimal:2',
        'date_created' => 'datetime',
        'date_updated' => 'datetime'
    ];

    // Scope untuk produk yang masih ada stok
    public function scopeInStock($query)
    {
        return $query->where('stok_total', '>', 0);
    }

    // Scope untuk pencarian produk
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama_produk', 'LIKE', "%{$search}%")
              ->orWhere('kd_produk', 'LIKE', "%{$search}%")
              ->orWhere('kd_int', 'LIKE', "%{$search}%")
              ->orWhere('kd_ext', 'LIKE', "%{$search}%")
              ->orWhere('jenis', 'LIKE', "%{$search}%")
              ->orWhere('material', 'LIKE', "%{$search}%");
        });
    }

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    // Accessor untuk gambar produk dengan fallback
    public function getImageUrlAttribute()
    {
        if ($this->gambar_produk && file_exists(public_path('images/products/' . $this->gambar_produk))) {
            return asset('images/products/' . $this->gambar_produk);
        }
        return asset('images/no-image.png'); // Default image
    }

    // Method untuk update stok
    public function updateStock($quantity, $type = 'keluar')
    {
        if ($type === 'keluar') {
            $this->stok_keluar += $quantity;
            $this->stok_total -= $quantity;
        } else {
            $this->stok_masuk += $quantity;
            $this->stok_total += $quantity;
        }
        
        $this->save();
    }

    // Method untuk cek apakah stok cukup
    public function hasEnoughStock($quantity)
    {
        return $this->stok_total >= $quantity;
    }

    /**
     * Get the product type (jenis) for this product.
     */
    public function produkJenis()
    {
        return $this->belongsTo(ProdukJenis::class, 'kd_produk_jenis', 'kd_produk_jenis');
    }

    /**
     * Get all stock entries for this product.
     */
    public function stoks()
    {
        return $this->hasMany(Stok::class, 'kd_produk', 'kd_produk');
    }

    /**
     * Get current stock total from stok table.
     */
    public function getStokTotalAttribute()
    {
        $masuk = $this->stoks()->sum('masuk');
        $keluar = $this->stoks()->sum('keluar');
        
        return $masuk - $keluar;
    }

    /**
     * Check if product has sufficient stock.
     */
    public function hasStock($qty)
    {
        return $this->stok_total >= $qty;
    }

    /**
     * Get all sales details for this product.
     */
    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class, 'kd_produk', 'kd_produk');
    }

    /**
     * Get the category name (with fallback to jenis field).
     */
    public function getCategoryNameAttribute()
    {
        // Try to get from relationship first
        if ($this->produkJenis) {
            return $this->produkJenis->nama;
        }
        
        // Fallback to jenis field
        return $this->jenis ?? 'Uncategorized';
    }
}

