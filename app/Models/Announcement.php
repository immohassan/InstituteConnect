<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'society_id',
        'is_pinned',
        'expires_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_pinned' => 'boolean',
        'expires_at' => 'datetime',
    ];
    
    /**
     * Get the user who created the announcement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the society that the announcement belongs to.
     */
    public function society()
    {
        return $this->belongsTo(Society::class);
    }
    
    /**
     * Scope a query to only include non-expired announcements.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where(function($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }
    
    /**
     * Scope a query to only include pinned announcements.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
    
    /**
     * Get all active announcements ordered by pinned status and creation date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');
    }
}