<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/**
 * Table: pfp_uploads
*
* === Columns ===
 * @property int $id
 * @property string $user
 * @property string $path
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
*
* === Relationships ===
 * @property-read \Laravel\Sanctum\PersonalAccessToken|null $tokens
*/
class pfp_uploads extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'user',
        'path'
    ];
}
