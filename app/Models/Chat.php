<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status', // requested, accepted, declined
    ];

    /**
     * Get the sender user.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver user.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the messages for the chat.
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Get the latest message for the chat.
     */
    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    /**
     * Check if the chat is accepted.
     */
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if the chat is pending.
     */
    public function isPending()
    {
        return $this->status === 'requested';
    }

    /**
     * Check if the chat is declined.
     */
    public function isDeclined()
    {
        return $this->status === 'declined';
    }

    /**
     * Get the other user in the chat (not the current user).
     */
    public function getOtherUser($userId)
    {
        return $this->sender_id == $userId ? $this->receiver : $this->sender;
    }

    /**
     * Scope a query to only include chats for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        });
    }

    /**
     * Scope a query to only include accepted chats.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope a query to only include pending chats.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'requested');
    }
}
