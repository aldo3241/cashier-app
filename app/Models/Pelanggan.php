<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pelanggan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kd_pelanggan';

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
        'kd_pelanggan',
        'panggilan',
        'nama_lengkap',
        'nama_lembaga',
        'telp',
        'alamat',
        'kecamatan',
        'kotakab',
        'provinsi',
        'negara',
        'kode_pos',
        'catatan',
        'date_updated',
        'dibuat_oleh',
        'date_created',
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
     * Get the display name for the customer.
     * Returns panggilan if available, otherwise nama_lengkap.
     */
    public function getDisplayNameAttribute()
    {
        return $this->panggilan ?: $this->nama_lengkap;
    }

    /**
     * Get the full name with title.
     * Example: "Bapak John Doe" or "Ibu Jane Smith"
     */
    public function getFullNameWithTitleAttribute()
    {
        $title = $this->panggilan ?: '';
        return trim($title . ' ' . $this->nama_lengkap);
    }

    /**
     * Get the complete address.
     */
    public function getFullAddressAttribute()
    {
        $address = [];
        
        if ($this->alamat) $address[] = $this->alamat;
        if ($this->kecamatan) $address[] = 'Kec. ' . $this->kecamatan;
        if ($this->kotakab) $address[] = $this->kotakab;
        if ($this->provinsi) $address[] = $this->provinsi;
        if ($this->negara && $this->negara !== 'Indonesia') $address[] = $this->negara;
        if ($this->kode_pos) $address[] = $this->kode_pos;
        
        return implode(', ', $address);
    }

    /**
     * Get the organization or personal name.
     */
    public function getIdentifierAttribute()
    {
        if ($this->nama_lembaga) {
            return $this->nama_lembaga . ' (' . $this->display_name . ')';
        }
        return $this->display_name;
    }

    /**
     * Scope a query to search customers.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'LIKE', "%{$search}%")
              ->orWhere('panggilan', 'LIKE', "%{$search}%")
              ->orWhere('nama_lembaga', 'LIKE', "%{$search}%")
              ->orWhere('telp', 'LIKE', "%{$search}%")
              ->orWhere('kd_pelanggan', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope a query to order by name.
     */
    public function scopeOrderByName($query)
    {
        return $query->orderBy('nama_lengkap', 'asc');
    }

    /**
     * Get formatted phone number.
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->telp) return '-';
        
        // Format Indonesian phone numbers
        $phone = preg_replace('/[^0-9]/', '', $this->telp);
        
        if (strlen($phone) >= 10) {
            // Format: 0812-3456-7890
            return preg_replace('/(\d{4})(\d{4})(\d{4})/', '$1-$2-$3', $phone);
        }
        
        return $this->telp;
    }

    /**
     * Check if customer has organization.
     */
    public function hasOrganization()
    {
        return !empty($this->nama_lembaga);
    }

    /**
     * Get customer type (personal/organization).
     */
    public function getTypeAttribute()
    {
        return $this->hasOrganization() ? 'organization' : 'personal';
    }

    /**
     * Check if this is the default walk-in customer.
     */
    public function isDefaultCustomer()
    {
        return $this->kd_pelanggan === '#PLG1';
    }

    /**
     * Get the default walk-in customer.
     * Creates one if it doesn't exist.
     */
    public static function getDefaultCustomer()
    {
        $customer = static::find('#PLG1');
        
        if (!$customer) {
            // Create default customer if it doesn't exist
            $customer = static::create([
                'kd_pelanggan' => '#PLG1',
                'panggilan' => 'Pelanggan',
                'nama_lengkap' => 'Pelanggan Umum',
                'nama_lembaga' => null,
                'telp' => '-',
                'alamat' => 'Walk-in Customer',
                'kecamatan' => null,
                'kotakab' => null,
                'provinsi' => null,
                'negara' => 'Indonesia',
                'kode_pos' => null,
                'catatan' => 'Default walk-in customer for cash transactions',
                'dibuat_oleh' => 'system',
                'date_created' => now(),
                'date_updated' => now(),
            ]);
        }
        
        return $customer;
    }

    /**
     * Get default customer code.
     */
    public static function getDefaultCustomerCode()
    {
        return '#PLG1';
    }
}

