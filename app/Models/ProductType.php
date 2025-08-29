<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $table = 'produk_jenis';
    protected $primaryKey = 'kd_produk_jenis';

    protected $fillable = [
        'nama',
    ];

    protected $casts = [
        'date_created' => 'datetime',
        'date_updated' => 'datetime',
    ];

    // Relationship with products
    public function products()
    {
        return $this->hasMany(Product::class, 'kd_produk_jenis', 'kd_produk_jenis');
    }
}
