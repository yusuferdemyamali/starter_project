<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'client_name',
        'company',
        'testimonial',
        'photo',
        'is_active',
        'order'
    ];
}
