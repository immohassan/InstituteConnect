<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'cover_image',
    ];
    
    /**
     * Get the leader of the society.
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }
    
    /**
     * Get the members of the society.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'society_user', 'society_id', 'user_id')
            ->withTimestamps();
    }
    
    /**
     * Get the announcements for the society.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
    
    /**
     * Get the sorted announcements for the society.
     */
    public function getAnnouncementsAttribute()
    {
        return $this->announcements()->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * Get the posts related to this society.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    /**
     * Get the sorted posts for the society.
     */
    public function getPostsAttribute()
    {
        return $this->posts()->orderBy('created_at', 'desc')->get();
    }

    public function followers()
{
    return $this->belongsToMany(User::class, 'society_user')->withTimestamps();
}

}