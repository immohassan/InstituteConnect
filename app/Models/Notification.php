<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type', // announcement, post, comment, like, chat_request, etc.
        'data', // JSON data specific to notification type
        'read_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include unread notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
    
    /**
     * Scope a query to only include read notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
    
    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->read_at = now();
            $this->save();
        }
    }
    
    /**
     * Determine if the notification has been read.
     *
     * @return bool
     */
    public function isRead()
    {
        return $this->read_at !== null;
    }
    
    /**
     * Get the formatted notification message based on the type.
     *
     * @return string
     */
    public function getMessageAttribute()
    {
        switch ($this->type) {
            case 'announcement':
                return $this->getAnnouncementMessage();
            case 'post':
                return $this->getPostMessage();
            case 'comment':
                return $this->getCommentMessage();
            case 'like':
                return $this->getLikeMessage();
            case 'chat_request':
                return $this->getChatRequestMessage();
            default:
                return 'New notification';
        }
    }
    
    /**
     * Get the URL for the notification based on the type.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        switch ($this->type) {
            case 'announcement':
                return route('announcements.show', $this->data['announcement_id']);
            case 'post':
                return route('posts.show', $this->data['post_id']);
            case 'comment':
                return route('posts.show', $this->data['post_id']) . '#comment-' . $this->data['comment_id'];
            case 'like':
                return route('posts.show', $this->data['post_id']);
            case 'chat_request':
                return route('chats.index');
            default:
                return '#';
        }
    }
    
    /**
     * Get the message for an announcement notification.
     *
     * @return string
     */
    private function getAnnouncementMessage()
    {
        $senderName = $this->data['sender_name'];
        $title = $this->data['title'];
        
        if (isset($this->data['society_name'])) {
            $societyName = $this->data['society_name'];
            return "{$senderName} posted a new announcement in {$societyName}: {$title}";
        }
        
        return "{$senderName} posted a new general announcement: {$title}";
    }
    
    /**
     * Get the message for a post notification.
     *
     * @return string
     */
    private function getPostMessage()
    {
        $senderName = $this->data['sender_name'];
        
        if (isset($this->data['society_name'])) {
            $societyName = $this->data['society_name'];
            return "{$senderName} created a new post in {$societyName}";
        }
        
        return "{$senderName} created a new post";
    }
    
    /**
     * Get the message for a comment notification.
     *
     * @return string
     */
    private function getCommentMessage()
    {
        $senderName = $this->data['sender_name'];
        return "{$senderName} commented on your post";
    }
    
    /**
     * Get the message for a like notification.
     *
     * @return string
     */
    private function getLikeMessage()
    {
        $senderName = $this->data['sender_name'];
        
        if ($this->data['content_type'] === 'post') {
            return "{$senderName} liked your post";
        }
        
        return "{$senderName} liked your comment";
    }
    
    /**
     * Get the message for a chat request notification.
     *
     * @return string
     */
    private function getChatRequestMessage()
    {
        $senderName = $this->data['sender_name'];
        return "{$senderName} wants to chat with you";
    }
}