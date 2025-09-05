<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'image',
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
        'status' => 'boolean',
    ];

    // Автоматическая проверка статуса при выборке
    protected static function booted()
    {
        static::retrieved(function ($certificate) {
            if ($certificate->deadline < Carbon::now() && $certificate->status) {
                $certificate->status = false;
                $certificate->save();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
