<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity',
        'sold',
        'sale_start',
        'sale_end',
        'benefits',
        'is_active',
        'min_purchase',
        'max_purchase',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_start' => 'datetime',
        'sale_end' => 'datetime',
        'benefits' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the event that owns the ticket.
     */
    public function event()
    {
        return $this->belongsTo(PartnerEvent::class, 'event_id');
    }

    /**
     * Get remaining quantity.
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->quantity - $this->sold;
    }

    /**
     * Check if ticket is available for sale.
     */
    public function isAvailable()
    {
        return $this->is_active 
            && $this->remaining_quantity > 0
            && now()->between($this->sale_start, $this->sale_end);
    }

    /**
     * Check if ticket sale has started.
     */
    public function saleStarted()
    {
        return now()->gte($this->sale_start);
    }

    /**
     * Check if ticket sale has ended.
     */
    public function saleEnded()
    {
        return now()->gt($this->sale_end);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get sale status.
     */
    public function getSaleStatusAttribute()
    {
        if (!$this->saleStarted()) {
            return 'Not Started';
        }
        
        if ($this->saleEnded()) {
            return 'Ended';
        }
        
        if ($this->remaining_quantity <= 0) {
            return 'Sold Out';
        }
        
        return 'Available';
    }

    /**
     * Get sale status color.
     */
    public function getSaleStatusColorAttribute()
    {
        return match($this->sale_status) {
            'Available' => 'bg-green-100 text-green-800',
            'Sold Out' => 'bg-red-100 text-red-800',
            'Ended' => 'bg-gray-100 text-gray-800',
            'Not Started' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
