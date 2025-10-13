<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penjualan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kd_penjualan';

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
        'kd_penjualan',
        'no_faktur_penjualan',
        'kd_pelanggan',
        'sub_total',
        'pajak',
        'total_harga',
        'total_bayar',
        'lebih_bayar',
        'status_bayar',
        'keuangan_kotak',
        'catatan',
        'status_barang',
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
        'sub_total' => 'decimal:2',
        'pajak' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'lebih_bayar' => 'decimal:2',
        'date_created' => 'datetime',
        'date_updated' => 'datetime',
    ];

    /**
     * Get the customer that owns the sale.
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'kd_pelanggan', 'kd_pelanggan');
    }

    /**
     * Get the payment method for the sale.
     */
    public function keuanganKotak()
    {
        return $this->belongsTo(KeuanganKotak::class, 'keuangan_kotak', 'nama');
    }

    /**
     * Get the sale details for the sale.
     */
    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class, 'kd_penjualan', 'kd_penjualan');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Delete related details when deleting a sale
        static::deleting(function ($model) {
            $model->penjualanDetails()->delete();
        });
    }

    /**
     * Generate unique invoice number.
     */
    public static function generateInvoiceNumber()
    {
        return 'PJ' . date('ymdHis');
    }

    /**
     * Generate unique sale ID.
     */
    public static function generateSaleId()
    {
        return 'PJ' . date('ymdHis') . rand(100, 999);
    }

    /**
     * Calculate total from details.
     */
    public function calculateTotals()
    {
        $subTotal = $this->penjualanDetails->sum('subtotal');
        $this->sub_total = $subTotal;
        $this->total_harga = $subTotal + $this->pajak;
        $this->lebih_bayar = $this->total_bayar - $this->total_harga;
        $this->save();
    }

    /**
     * Check if sale is paid.
     */
    public function isPaid()
    {
        return $this->status_bayar === 'Lunas';
    }

    /**
     * Check if sale is completed.
     */
    public function isCompleted()
    {
        return $this->status_barang === 'diterima langsung';
    }

    /**
     * Scope a query to filter by payment status.
     */
    public function scopePaid($query)
    {
        return $query->where('status_bayar', 'Lunas');
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_created', [$startDate, $endDate]);
    }

    /**
     * Scope a query to order by creation date.
     */
    public function scopeOrderByDate($query, $direction = 'desc')
    {
        return $query->orderBy('date_created', $direction);
    }

    /**
     * Safely delete a sale with all its details.
     */
    public function deleteWithDetails()
    {
        DB::transaction(function () {
            // Delete all related details first
            $this->penjualanDetails()->delete();
            
            // Then delete the sale
            $this->delete();
        });
    }
}
