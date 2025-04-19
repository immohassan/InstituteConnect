<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'avatar',
        'role',
        'enrollment_number',
        'department',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get all societies that the user is a member of.
     */
    public function societies()
    {
        return $this->belongsToMany(Society::class, 'society_user', 'user_id', 'society_id')
            ->withTimestamps();
    }
    
    /**
     * Get the societies that the user leads.
     */
    public function ledSocieties()
    {
        return $this->hasMany(Society::class, 'leader_id');
    }
    
    /**
     * Get the posts created by the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    /**
     * Get the sorted posts created by the user.
     */
    public function getPostsAttribute()
    {
        return $this->posts()->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * Get the comments created by the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    /**
     * Get the sorted comments created by the user.
     */
    public function getCommentsAttribute()
    {
        return $this->comments()->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * Get the likes created by the user.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    /**
     * Get the announcements created by the user.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
    
    /**
     * Get the sorted announcements created by the user.
     */
    public function getAnnouncementsAttribute()
    {
        return $this->announcements()->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * Get the results associated with the user.
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }
    
    /**
     * Get the attendance records associated with the user.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    /**
     * Get the subjects taught by the user (if they are a teacher).
     */
    public function taughtSubjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }
    
    /**
     * Get the notifications for the user.
     */
    public function userNotifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    /**
     * Get the sorted notifications for the user.
     */
    public function getNotificationsAttribute()
    {
        return $this->userNotifications()->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * Get the chats where the user is participant.
     */
    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_user', 'user_id', 'chat_id')
            ->withTimestamps();
    }
    
    /**
     * Get the messages sent by the user.
     */
    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }
    
    /**
     * Get the sorted messages sent by the user.
     */
    public function getSentMessagesAttribute()
    {
        return $this->sentMessages()->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public static function find($id)
    {
        return static::query()->where('id', $id)->first();
    }
    
    /**
     * Scope a query to filter by column.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $column
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhere($query, $column, $operator = null, $value = null)
    {
        return $query->where($column, $operator, $value);
    }
    
    /**
     * Check if the user has a specific role.
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role, $roles);
    }
    
    /**
     * Check if the user is an admin or super admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }
    
    /**
     * Check if the user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }
    
    /**
     * Check if the user is a society leader (sub-admin).
     *
     * @return bool
     */
    public function isSubAdmin()
    {
        return $this->role === 'sub_admin';
    }
}