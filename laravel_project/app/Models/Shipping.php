<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping';

    protected $fillable = [
        'name',
        'description',
        'cost',
        'estimated_days',
        'is_active'
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'estimated_days' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCost($query, $minCost = null, $maxCost = null)
    {
        if ($minCost !== null) {
            $query->where('cost', '>=', $minCost);
        }
        
        if ($maxCost !== null) {
            $query->where('cost', '<=', $maxCost);
        }
        
        return $query;
    }

    public function scopeByEstimatedDays($query, $days)
    {
        return $query->where('estimated_days', '<=', $days);
    }

    // Methods
    public function getFormattedCostAttribute()
    {
        return '$' . number_format($this->cost, 2);
    }

    public function getEstimatedDeliveryDate()
    {
        return now()->addDays($this->estimated_days);
    }

    public function isFastShipping()
    {
        return $this->estimated_days <= 2;
    }

    public function isStandardShipping()
    {
        return $this->estimated_days > 2 && $this->estimated_days <= 5;
    }

    public function isEconomyShipping()
    {
        return $this->estimated_days > 5;
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
}
