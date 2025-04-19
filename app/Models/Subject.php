<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'semester',
        'credits',
        'teacher_id',
    ];
    
    /**
     * Get the teacher who teaches this subject.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    /**
     * Get the results associated with the subject.
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }
    
    /**
     * Get the attendances associated with the subject.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}