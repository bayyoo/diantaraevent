<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_date',
        'event_time',
        'location',
        'capacity',
        'flyer_path',
        'created_by',
        'price',
        'allow_multiple_registration',
        'max_registrations_per_user',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
        'approved_at' => 'datetime',
    ];

    // Relationship: User who created the event
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship: Admin who approved the event
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope: Only approved/published events
    public function scopePublished($query)
    {
        return $query->whereIn('status', ['approved', 'published']);
    }

    // Scope: Pending events
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Check if event is pending approval
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // Check if event is approved
    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'published']);
    }

    // Check if event is rejected
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'event_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function eventAttendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function eventCertificates(): HasMany
    {
        return $this->hasMany(EventCertificate::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class)->orderBy('order_index');
    }

    /**
     * Check if event has ended (for certificate generation)
     */
    public function hasEnded(): bool
    {
        // If sessions exist, use last session end time
        $lastSession = $this->sessions()->orderByDesc('end_at')->first();
        if ($lastSession && $lastSession->end_at) {
            return now()->greaterThan($lastSession->end_at);
        }
        // Fallback to original single date logic
        $eventEndTime = \Carbon\Carbon::parse(($this->event_date ?? now()) . ' ' . ($this->event_time ?? '00:00:00'))->addHours(3);
        return now()->greaterThan($eventEndTime);
    }

    /**
     * Check if certificates should be available for download
     */
    public function certificatesAvailable(): bool
    {
        return $this->has_certificate && $this->hasEnded();
    }
}
