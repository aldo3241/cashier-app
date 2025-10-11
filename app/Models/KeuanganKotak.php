<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuanganKotak extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keuangan_kotak';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kd_keuangan_kotak';

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
        'kd_keuangan_kotak',
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
     * Get all penjualan for this payment method.
     */
    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'keuangan_kotak', 'nama');
    }

    /**
     * Scope a query to order by name.
     */
    public function scopeOrderByName($query)
    {
        return $query->orderBy('nama', 'asc');
    }
}
