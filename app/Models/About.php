<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'location',
        'schedule',
        'phone',
        'instagram',
        'facebook',
        'tiktok',
        'video_ref',
        'banner_url',
    ];

}
