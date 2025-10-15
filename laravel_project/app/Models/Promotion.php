<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByDiscountType($query, $type)
    {
        return $query->where('discount_type', $type);
    }

    // Methods
    public function isActive()
    {
        $now = now();
        return $this->is_active && 
               $this->start_date <= $now && 
               $this->end_date >= $now;
    }

    public function isUpcoming()
    {
        return $this->start_date > now();
    }

    public function isExpired()
    {
        return $this->end_date < now();
    }

    public function getDiscountedPrice($originalPrice)
    {
        if (!$this->isActive()) {
            return $originalPrice;
        }

        if ($this->discount_type === 'percentage') {
            $discount = ($originalPrice * $this->discount_value) / 100;
            return max(0, $originalPrice - $discount);
        } else {
            return max(0, $originalPrice - $this->discount_value);
        }
    }

    public function getDiscountAmount($originalPrice)
    {
        if (!$this->isActive()) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return ($originalPrice * $this->discount_value) / 100;
        } else {
            return $this->discount_value;
        }
    }

    public function getFormattedDiscountValueAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        } else {
            return '$' . number_format($this->discount_value, 2);
        }
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->isExpired()) {
            return 0;
        }

        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    public function toggleActive()
    {
        $this->update(['is_active' => !$this->is_active]);
    }

    public function extendEndDate($days)
    {
        $this->update(['end_date' => $this->end_date->addDays($days)]);
    }

    public function getStatusAttribute()
    {
        if ($this->isExpired()) {
            return 'expired';
        } elseif ($this->isUpcoming()) {
            return 'upcoming';
        } elseif ($this->isActive()) {
            return 'active';
        } else {
            return 'inactive';
        }
    }
}
