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
        'role_id',
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

    /**
     * Get the role that belongs to the user
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Override role attribute to return relationship when role_id exists
     */
    public function getRoleAttribute($value)
    {
        // If we have a role_id, return the relationship object
        if ($this->role_id) {
            return $this->getRelationValue('role');
        }
        
        // Otherwise return the old string value
        return $value;
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            // Check by role name (for backward compatibility)
            if ($this->role_id) {
                $roleModel = $this->role()->first();
                if ($roleModel) {
                    return $roleModel->name === $role;
                }
            }
            // Fallback to old role column
            return $this->attributes['role'] === $role;
        }
        
        if (is_object($role)) {
            return $this->role_id === $role->id;
        }
        
        return false;
    }

    /**
     * Check if user is admin (backward compatibility)
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is cashier (backward compatibility)
     */
    public function isCashier()
    {
        return $this->hasRole('cashier');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->role_id) {
            return false;
        }
        
        $role = $this->role()->first();
        if (!$role) {
            return false;
        }
        
        return $role->hasPermission($permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get user role display name
     */
    public function getRoleDisplayName()
    {
        // Check if user has a role relationship (new system)
        if ($this->role_id) {
            $role = $this->role()->first();
            if ($role) {
                return $role->display_name;
            }
        }
        
        // Fallback to old role system
        return match($this->attributes['role'] ?? '') {
            'admin' => 'Administrator',
            'cashier' => 'Cashier',
            default => 'User'
        };
    }

    /**
     * Get user role name
     */
    public function getRoleName()
    {
        // Check if user has a role relationship (new system)
        if ($this->role_id) {
            $role = $this->role()->first();
            if ($role) {
                return $role->name;
            }
        }
        
        return $this->attributes['role'] ?? 'user';
    }
}
