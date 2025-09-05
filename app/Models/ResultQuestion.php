<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_id',
        'title',
        'correct_answer',
        'user_answer',
        'status',
    ];

    public function result()
    {
        return $this->belongsTo(Result::class);
    }
}
