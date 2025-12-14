<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'score',
        'duration_minutes',
        // ✅ Добавлено
        'company_id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Получить компанию, которой принадлежит экзамен.
     */
    public function company(): BelongsTo
    {
        // Предполагаем, что ваша модель Company называется App\Models\Company
        return $this->belongsTo(Company::class);
    }
}