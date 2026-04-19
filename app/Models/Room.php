<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role_id', 'phone', 'address', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // Relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relasi ke profil customer (jika user adalah pelanggan)
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    // Helper: cek apakah user adalah admin
    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    // Helper: cek apakah user adalah pelanggan
    public function isCustomer(): bool
    {
        return $this->role?->name === 'pelanggan';
    }
}
