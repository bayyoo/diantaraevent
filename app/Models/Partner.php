<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Partner extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'organization_name',
        'address',
        'status',
        'email_verified_at',
        'verified_at',
        'verified_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the organizations for the partner.
     */
    public function organizations()
    {
        return $this->hasMany(PartnerOrganization::class);
    }

    /**
     * Get the events for the partner.
     */
    public function events()
    {
        return $this->hasMany(PartnerEvent::class);
    }

    /**
     * Get the admin who verified this partner.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if partner is verified.
     */
    public function isVerified()
    {
        return $this->status === 'verified';
    }

    /**
     * Check if partner is pending verification.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
