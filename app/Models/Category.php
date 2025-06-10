<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Category extends Model
{
    public $timestamps = true; 

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'is_active',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::instance($date)->setTimezone('America/Lima')->toDateTimeString();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
