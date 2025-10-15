<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keuangan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kd_keuangan';

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
        'masuk',
        'keluar',
        'catatan',
        'referensi',
        'dibuat_oleh',
        'keuangan_kotak',
        'keuangan_kategori',
        'date_created',
        'date_updated',
        'gambar',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'masuk' => 'decimal:2',
        'keluar' => 'decimal:2',
        'date_created' => 'datetime',
        'date_updated' => 'datetime',
    ];

    /**
     * Get the payment method for this financial record.
     */
    public function keuanganKotak()
    {
        return $this->belongsTo(KeuanganKotak::class, 'keuangan_kotak', 'nama');
    }

    /**
     * Create a financial mutation for a completed sale.
     */
    public static function createSaleMutation($penjualan)
    {
        return static::create([
            'masuk' => $penjualan->total_harga,
            'keluar' => 0, // Always 0 for sales (money coming in)
            'catatan' => null,
            'referensi' => $penjualan->no_faktur_penjualan,
            'dibuat_oleh' => $penjualan->dibuat_oleh,
            'keuangan_kotak' => $penjualan->keuangan_kotak,
            'keuangan_kategori' => 'Penjualan',
            'date_created' => now(),
            'date_updated' => now(),
            'gambar' => null,
        ]);
    }
}
