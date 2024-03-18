<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class messages extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'chat',
        'role',
        'content',
        'usage',
        'openai_id'
    ];

    protected $casts = [
        'usage'=> AsArrayObject::class
    ];

    protected $attributes = [
        'usage'=> '{
            "prompt_tokens": 0,
            "completion_tokens": 0,
            "total_tokens": 0
        }',
    ];
}
