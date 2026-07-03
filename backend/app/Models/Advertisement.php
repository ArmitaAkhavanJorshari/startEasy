<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'contact',
        'image_url',
    ];
}
