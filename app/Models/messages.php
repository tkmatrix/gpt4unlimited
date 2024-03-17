<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

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
}
