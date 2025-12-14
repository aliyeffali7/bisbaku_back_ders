<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'role',
        'password',
        'company_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}