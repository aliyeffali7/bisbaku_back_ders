<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'full_description',
        'price',
        'image',
        'education_document',
        'contract_document',
        'certificate_image',
        // ✅ Добавлено
        'company_id' 
    ];

    /**
     * Получить компанию, которой принадлежит курс.
     */
    public function company(): BelongsTo
    {
        // Предполагаем, что ваша модель Company называется App\Models\Company
        return $this->belongsTo(Company::class);
    }
}