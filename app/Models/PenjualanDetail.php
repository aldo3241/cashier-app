<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penjualan_detail';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kd_penjualan_detail';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->kd_penjualan_detail)) {
                $model->kd_penjualan_detail = time() + rand(10000, 99999);
            }
        });
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kd_penjualan_detail',
        'kd_penjualan',
        'kd_produk',
        'nama_produk',
        'produk_jenis',
        'kd_pemasok',
        'pemasok',
        'sistem_bayar',
        'hpp',
        'harga_jual',
        'qty',
        'diskon',
        'laba',
        'status_bayar',
        'catatan',
        'date_created',
        'date_updated',
        'dibuat_oleh',
        'no_faktur_penjualan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hpp' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'qty' => 'integer',
        'diskon' => 'decimal:2',
        'laba' => 'decimal:2',
        'date_created' => 'datetime',
        'date_updated' => 'datetime',
    ];

    /**
     * Get the subtotal attribute (calculated).
     */
    public function getSubtotalAttribute()
    {
        return ($this->harga_jual * $this->qty) - $this->diskon;
    }

    /**
     * Get the sale that owns the detail.
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'kd_penjualan', 'kd_penjualan');
    }

    /**
     * Get the product that owns the detail.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
    }

    /**
     * Get the product type for this detail.
     */
    public function produkJenis()
    {
        return $this->belongsTo(ProdukJenis::class, 'produk_jenis', 'kd_produk_jenis');
    }

    /**
     * Calculate subtotal based on qty, price, and discount.
     */
    public function calculateSubtotal()
    {
        $subtotal = ($this->harga_jual * $this->qty) - $this->diskon;
        $this->subtotal = $subtotal;
        return $subtotal;
    }

    /**
     * Calculate profit (laba).
     */
    public function calculateProfit()
    {
        $profit = ($this->harga_jual - $this->hpp) * $this->qty;
        $this->laba = $profit;
        return $profit;
    }

    /**
     * Generate unique detail ID.
     */
    public static function generateDetailId()
    {
        return 'PD' . time() . rand(100, 999);
    }

    /**
     * Create detail from cart item.
     */
    public static function createFromCartItem($penjualanId, $cartItem, $invoiceNumber)
    {
        $produk = Produk::find($cartItem['kd_produk']);
        
        if (!$produk) {
            throw new \Exception("Product not found: {$cartItem['kd_produk']}");
        }

        // Check stock availability
        $currentStock = Stok::getCurrentStock($cartItem['kd_produk']);
        if ($currentStock < $cartItem['qty']) {
            throw new \Exception("Insufficient stock for {$produk->nama}. Available: {$currentStock}, Requested: {$cartItem['qty']}");
        }

        $detail = static::create([
            'kd_penjualan_detail' => static::generateDetailId(),
            'kd_penjualan' => $penjualanId,
            'kd_produk' => $cartItem['kd_produk'],
            'nama_produk' => $cartItem['nama'],
            'produk_jenis' => $produk->kd_produk_jenis,
            'kd_pemasok' => $produk->kd_pemasok,
            'pemasok' => $produk->pemasok,
            'sistem_bayar' => null,
            'hpp' => $produk->hpp ?? 0,
            'harga_jual' => $cartItem['harga'],
            'qty' => $cartItem['qty'],
            'diskon' => $cartItem['diskon'] ?? 0,
            'subtotal' => 0, // Will be calculated
            'laba' => 0, // Will be calculated
            'status_bayar' => 'Lunas',
            'catatan' => null,
            'dibuat_oleh' => auth()->user()->name ?? 'system',
            'no_faktur_penjualan' => $invoiceNumber,
            'date_created' => now(),
            'date_updated' => now(),
        ]);

        // Calculate subtotal and profit
        $detail->calculateSubtotal();
        $detail->calculateProfit();
        $detail->save();

        // Reduce stock
        Stok::reduceStock(
            $cartItem['kd_produk'],
            $cartItem['qty'],
            'Penjualan',
            $invoiceNumber,
            "Sale: {$invoiceNumber}",
            auth()->user()->name ?? 'system'
        );

        return $detail;
    }
}
