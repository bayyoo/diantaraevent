<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCertificate extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'certificate_number',
        'participant_name',
        'certificate_file_path',
        'generated_at',
        'download_count',
        'last_downloaded_at'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'last_downloaded_at' => 'datetime'
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
     * Generate unique certificate number
     */
    public static function generateCertificateNumber(): string
    {
        $prefix = 'CERT';
        $year = date('Y');
        
        do {
            $number = $prefix . $year . str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('certificate_number', $number)->exists());
        
        return $number;
    }

    /**
     * Increment download count
     */
    public function incrementDownload(): void
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => now()]);
    }

    /**
     * Get certificate file URL
     */
    public function getCertificateUrlAttribute(): string
    {
        return asset('storage/' . $this->certificate_file_path);
    }
}
