<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukJenis extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produk_jenis';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kd_produk_jenis';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kd_produk_jenis',
        'nama',
        'date_created',
        'date_updated',
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
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the products for this product type.
     */
    public function produks()
    {
        return $this->hasMany(Produk::class, 'kd_produk_jenis', 'kd_produk_jenis');
    }

    /**
     * Scope a query to order by name.
     */
    public function scopeOrderByName($query)
    {
        return $query->orderBy('nama', 'asc');
    }

    /**
     * Get all active product types with product count.
     */
    public static function withProductCount()
    {
        return static::withCount('produks')->orderByName()->get();
    }
}

