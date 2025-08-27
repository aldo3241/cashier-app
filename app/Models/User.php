<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'akun';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'kd';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'photo_profile',
        'dibuat_oleh',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_created' => 'datetime',
            'date_updated' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the name attribute (alias for nama)
     */
    public function getNameAttribute()
    {
        return $this->nama;
    }

    /**
     * Set the name attribute (alias for nama)
     */
    public function setNameAttribute($value)
    {
        $this->attributes['nama'] = $value;
    }

    /**
     * Get the timestamps attribute names
     */
    public function getCreatedAtColumn()
    {
        return 'date_created';
    }

    public function getUpdatedAtColumn()
    {
        return 'date_updated';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
