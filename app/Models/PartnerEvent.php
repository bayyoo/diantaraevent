<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'organization_id',
        'title',
        'slug',
        'description',
        'category',
        'start_date',
        'end_date',
        'location',
        'location_details',
        'poster',
        'banners',
        'terms_conditions',
        'status',
        'published_at',
        'approved_by',
        'approved_at',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
        'location_details' => 'array',
        'banners' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the partner that owns the event.
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the organization that owns the event.
     */
    public function organization()
    {
        return $this->belongsTo(PartnerOrganization::class, 'organization_id');
    }

    /**
     * Get the tickets for the event.
     */
    public function tickets()
    {
        return $this->hasMany(PartnerTicket::class, 'event_id');
    }

    /**
     * Get the admin who approved this event.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if event is published.
     */
    public function isPublished()
    {
        return $this->status === 'published';
    }

    /**
     * Check if event is pending review.
     */
    public function isPendingReview()
    {
        return $this->status === 'pending_review';
    }

    /**
     * Check if event is draft.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Reviews (polymorphic)
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'published' => 'bg-green-100 text-green-800',
            'pending_review' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get formatted status.
     */
    public function getFormattedStatusAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }
}
