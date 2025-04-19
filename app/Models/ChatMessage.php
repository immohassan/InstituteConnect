<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the chat that owns the message.
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Get the user that sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->read_at = now();
            $this->save();
        }
    }

    /**
     * Check if the message is read.
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include unread messages for a specific user.
     */
    public function scopeUnreadForUser($query, $userId)
    {
        return $query->whereHas('chat', function ($q) use ($userId) {
            $q->where(function ($inner) use ($userId) {
                $inner->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            });
        })->where('user_id', '!=', $userId)
          ->whereNull('read_at');
    }
}
