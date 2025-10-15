<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shipping_id',
        'tracking_number',
        'state',
        'delivery_at',
        'shipping_at'
    ];

    protected $casts = [
        'delivery_at' => 'datetime',
        'shipping_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    // Scopes
    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function scopePending($query)
    {
        return $query->where('state', 'pending');
    }

    public function scopeDelivered($query)
    {
        return $query->where('state', 'delivered');
    }

    public function scopeInTransit($query)
    {
        return $query->whereIn('state', ['shipped', 'in_transit', 'out_for_delivery']);
    }

    // Methods
    public function markAsShipped()
    {
        $this->update([
            'state' => 'shipped',
            'shipping_at' => now()
        ]);
    }

    public function markAsDelivered()
    {
        $this->update([
            'state' => 'delivered',
            'delivery_at' => now()
        ]);
    }

    public function updateState($state)
    {
        $this->update(['state' => $state]);
    }

    public function getEstimatedDeliveryDate()
    {
        if ($this->shipping_at) {
            // Add shipping time based on shipping method
            return $this->shipping_at->addDays($this->shipping->estimated_days ?? 3);
        }
        return null;
    }

    public function isDelivered()
    {
        return $this->state === 'delivered';
    }

    public function isInTransit()
    {
        return in_array($this->state, ['shipped', 'in_transit', 'out_for_delivery']);
    }

    public function canBeCancelled()
    {
        return in_array($this->state, ['pending', 'processing']);
    }
}
