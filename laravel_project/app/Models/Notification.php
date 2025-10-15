<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message_id',
        'type',
        'is_read',
        'read_at'
    ];

    protected $casts = [
       
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    public function toggleRead()
    {
        if ($this->is_read) {
            $this->markAsUnread();
        } else {
            $this->markAsRead();
        }
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M j, Y g:i A');
    }

    public function getFormattedReadAtAttribute()
    {
        return $this->read_at ? $this->read_at->format('M j, Y g:i A') : null;
    }

    public function getShortBodyAttribute()
    {
        return strlen($this->body) > 100 
            ? substr($this->body, 0, 100) . '...' 
            : $this->body;
    }

    public function getIconAttribute()
    {
        return match($this->type) {
            'message' => '💬',
            'order' => '📦',
            'promotion' => '🎉',
            'system' => '🔔',
            default => '📢'
        };
    }

    public function getColorAttribute()
    {
        return match($this->type) {
            'message' => 'blue',
            'order' => 'green',
            'promotion' => 'orange',
            'system' => 'red',
            default => 'gray'
        };
    }

    // Static methods for creating notifications
    public static function createMessageNotification($userId, $messageId, $title, $body, $data = [])
    {
        return static::create([
            'user_id' => $userId,
            'message_id' => $messageId,
            'type' => 'message',
            'title' => $title,
            'body' => $body,
            'data' => $data
        ]);
    }

    public static function createOrderNotification($userId, $title, $body, $data = [])
    {
        return static::create([
            'user_id' => $userId,
            'type' => 'order',
            'title' => $title,
            'body' => $body,
            'data' => $data
        ]);
    }

    public static function createPromotionNotification($userId, $title, $body, $data = [])
    {
        return static::create([
            'user_id' => $userId,
            'type' => 'promotion',
            'title' => $title,
            'body' => $body,
            'data' => $data
        ]);
    }

    public static function createSystemNotification($userId, $title, $body, $data = [])
    {
        return static::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => $title,
            'body' => $body,
            'data' => $data
        ]);
    }
}
