<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'name',
        'email',
        'phone',
        'token',
        'status',
        'certificate_path',
        'ticket_path',
        'order_id',
        'payment_status',
        'amount',
        'snap_token',
        'attendance_token',
        'paid_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
