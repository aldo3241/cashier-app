<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'kd_produk';
    public $incrementing = true;
    public $timestamps = false; // Disable automatic timestamps

    // Ensure no fields are guarded
    protected $guarded = [];

    // Custom timestamp column names
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    /**
     * Get a fresh timestamp for the model.
     */
    public function freshTimestamp()
    {
        return now();
    }

    /**
     * Get a fresh timestamp for the model.
     */
    public function freshTimestampString()
    {
        return $this->fromDateTime($this->freshTimestamp());
    }

    /**
     * Get the name of the "created at" column.
     */
    public function getCreatedAtColumn()
    {
        return 'date_created';
    }

    /**
     * Get the name of the "updated at" column.
     */
    public function getUpdatedAtColumn()
    {
        return 'date_updated';
    }

    /**
     * Save the model to the database.
     */
    public function save(array $options = [])
    {
        // Ensure timestamps are disabled
        $this->timestamps = false;
        
        // Set custom date fields if they're not set
        if (!$this->date_created) {
            $this->date_created = now();
        }
        if (!$this->date_updated) {
            $this->date_updated = now();
        }
        
        return parent::save($options);
    }

    protected $fillable = [
        'kd_produk',
        'kd_produk_jenis',
        'kd_pemasok',
        'nama_produk',
        'gambar_produk',
        'material',
        'spesifik',
        'ukuran',
        'satuan',
        'berat',
        'stok_total',
        'hpp',
        'harga_jual',
        'pemasok',
        'dibuat_oleh',
        'date_created',
        'date_updated',
        'barcode',
    ];

    protected $casts = [
        'hpp' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'prediksi_laba' => 'decimal:2',
        'stok_total' => 'integer',
        'berat' => 'integer',
        'date_created' => 'datetime',
        'date_updated' => 'datetime',
    ];

    // Relationships
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'kd_produk_jenis', 'kd_produk_jenis');
    }

    // Accessors
    public function getCalculatedStockAttribute()
    {
        $stockIn = \DB::table('stok')->where('kd_produk', $this->kd_produk)->sum('masuk');
        $stockOut = \DB::table('stok')->where('kd_produk', $this->kd_produk)->sum('keluar');
        return $stockIn - $stockOut;
    }

    public function getStockStatusAttribute()
    {
        $stock = $this->stok_total;
        if ($stock <= 0) {
            return 'Out of Stock';
        } elseif ($stock <= 10) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }

    public function getStockStatusClassAttribute()
    {
        $stock = $this->stok_total;
        if ($stock <= 0) {
            return 'danger';
        } elseif ($stock <= 10) {
            return 'warning';
        } else {
            return 'success';
        }
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    public function getFormattedHppAttribute()
    {
        return 'Rp ' . number_format($this->hpp, 0, ',', '.');
    }
}
