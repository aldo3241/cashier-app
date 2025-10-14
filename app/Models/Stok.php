<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stok';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kd_stok';

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
    public $incrementing = true;

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
        'kd_produk',
        'masuk',
        'keluar',
        'klasifikasi',
        'no_ref',
        'catatan',
        'date_created',
        'date_updated',
        'dibuat_oleh',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_created' => 'datetime',
        'date_updated' => 'datetime',
    ];

    /**
     * Get the product that owns the stock.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
    }

    /**
     * Calculate current stock for a product.
     */
    public static function getCurrentStock($kd_produk)
    {
        $masuk = static::where('kd_produk', $kd_produk)->sum('masuk');
        $keluar = static::where('kd_produk', $kd_produk)->sum('keluar');
        
        return $masuk - $keluar;
    }

    /**
     * Add stock entry (masuk).
     */
    public static function addStock($kd_produk, $qty, $klasifikasi = 'Manual', $no_ref = null, $catatan = null, $dibuat_oleh = 'system')
    {
        return static::create([
            'kd_produk' => $kd_produk,
            'masuk' => $qty,
            'keluar' => 0,
            'klasifikasi' => $klasifikasi,
            'no_ref' => $no_ref,
            'catatan' => $catatan,
            'dibuat_oleh' => $dibuat_oleh,
            'date_created' => now(),
            'date_updated' => now(),
        ]);
    }

    /**
     * Reduce stock entry (keluar).
     */
    public static function reduceStock($kd_produk, $qty, $klasifikasi = 'Penjualan', $no_ref = null, $catatan = null, $dibuat_oleh = 'system')
    {
        return static::create([
            'kd_produk' => $kd_produk,
            'masuk' => 0,
            'keluar' => $qty,
            'klasifikasi' => $klasifikasi,
            'no_ref' => $no_ref,
            'catatan' => $catatan,
            'dibuat_oleh' => $dibuat_oleh,
            'date_created' => now(),
            'date_updated' => now(),
        ]);
    }
}
