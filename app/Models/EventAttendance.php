<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttendance extends Model
{
    protected $fillable = [
        'event_id',
        'user_id', 
        'attendance_token',
        'is_attended',
        'attended_at',
        'verified_by'
    ];

    protected $casts = [
        'is_attended' => 'boolean',
        'attended_at' => 'datetime'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique 10-digit attendance token
     */
    public static function generateToken(): string
    {
        do {
            $token = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (self::where('attendance_token', $token)->exists());
        
        return $token;
    }

    /**
     * Mark attendance as verified
     */
    public function markAttended(string $verifiedBy): bool
    {
        return $this->update([
            'is_attended' => true,
            'attended_at' => now(),
            'verified_by' => $verifiedBy
        ]);
    }
}
