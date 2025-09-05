<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'score',
        'duration_minutes',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
