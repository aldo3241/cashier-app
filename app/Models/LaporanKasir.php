<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKasir extends Model
{
    use HasFactory;

    protected $table = 'laporan_kasir';
    protected $primaryKey = 'kd_laporan_kasir';
    public $timestamps = false; // Assuming no standard created_at/updated_at based on strict column list

    protected $fillable = [
        'mulai',
        'akhir',
        'pemasukkan',
        'koreksi_pemasukkan',
        'pengeluaran',
        'koreksi_pengeluaran',
        'laba_kotor',
        'catatan',
        'dibuat_oleh',
        'date_created',
        'date_updated'
    ];

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $casts = [
        'mulai' => 'datetime',
        'akhir' => 'datetime',
        'pemasukkan' => 'decimal:2',
        'koreksi_pemasukkan' => 'decimal:2',
        'pengeluaran' => 'decimal:2',
        'koreksi_pengeluaran' => 'decimal:2',
        'laba_kotor' => 'decimal:2',
        'date_created' => 'datetime',
        'date_updated' => 'datetime',
    ];
}
