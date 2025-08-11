<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'position',
        'photo',
        'biography',
        'email',
        'phone',
        'linkedin',
        'is_active',
        'order',
    ];
}
