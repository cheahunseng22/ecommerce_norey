<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'rating',
        'comment',
        'review_date',
        'is_approved'
    ];

    protected $casts = [
        'rating' => 'integer',
        'review_date' => 'datetime',
        'is_approved' => 'boolean',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('review_date', '>=', now()->subDays($days));
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('review_date', 'desc');
    }

    // Methods
    public function approve()
    {
        $this->update(['is_approved' => true]);
    }

    public function disapprove()
    {
        $this->update(['is_approved' => false]);
    }

    public function toggleApproval()
    {
        $this->update(['is_approved' => !$this->is_approved]);
    }

    public function getFormattedRatingAttribute()
    {
        return str_repeat('⭐', $this->rating);
    }

    public function getFormattedReviewDateAttribute()
    {
        return $this->review_date->format('M j, Y g:i A');
    }

    public function getTimeAgoAttribute()
    {
        return $this->review_date->diffForHumans();
    }

    public function getShortCommentAttribute()
    {
        return strlen($this->comment) > 100 
            ? substr($this->comment, 0, 100) . '...' 
            : $this->comment;
    }

    public function isHighRating()
    {
        return $this->rating >= 4;
    }

    public function isLowRating()
    {
        return $this->rating <= 2;
    }

    public function isMediumRating()
    {
        return $this->rating == 3;
    }
}
