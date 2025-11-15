<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerOrganization extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'name',
        'type',
        'description',
        'website',
        'logo',
        'contact_info',
        'business_info',
        'status',
    ];

    protected $casts = [
        'contact_info' => 'array',
        'business_info' => 'array',
    ];

    /**
     * Get the partner that owns the organization.
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the events for the organization.
     */
    public function events()
    {
        return $this->hasMany(PartnerEvent::class, 'organization_id');
    }

    /**
     * Check if organization is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get contact information.
     */
    public function getContactAttribute($key)
    {
        return $this->contact_info[$key] ?? null;
    }

    /**
     * Get business information.
     */
    public function getBusinessAttribute($key)
    {
        return $this->business_info[$key] ?? null;
    }
}
