<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subject_id',
        'semester',
        'score',
        'grade',
        'remarks',
    ];
    
    /**
     * Get the user that owns the result.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the subject associated with the result.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    /**
     * Scope a query to only include results for a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhere($query, $column, $operator = null, $value = null)
    {
        return $query->where($column, $operator, $value);
    }
}