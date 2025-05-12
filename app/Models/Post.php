<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'image',
        'user_id',
        'society_id',
    ];
    
    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the society that the post belongs to (if any).
     */
    public function society()
    {
        return $this->belongsTo(Society::class);
    }
    
    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }
    
    /**
     * Get all of the likes for the post.
     */
public function likes()
{
    return $this->hasMany(Like::class);
}
    
    /**
     * Get the first image path for the post.
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return null;
    }
    
    /**
     * Get the 50 character excerpt from the post content.
     *
     * @return string
     */
    public function getExcerptAttribute()
    {
        return strlen($this->content) > 50 ? substr($this->content, 0, 50) . '...' : $this->content;
    }
    
    /**
     * Determine if the post has been liked by the given user.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isLikedBy(User $user)
    {
        return $this->likes->contains('user_id', $user->id);
    }
    public function attachments()
{
    return $this->hasMany(PostAttachment::class);
}
    /**
     * Scope a query to only include posts from societies the user belongs to.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisibleTo($query, User $user)
    {
        $societyIds = $user->societies->pluck('id')->toArray();
        
        return $query->where(function($query) use ($societyIds) {
            $query->whereIn('society_id', $societyIds)
                  ->orWhereNull('society_id');
        });
    }
}