<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    public $timestamps = true; 

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image_url',
        'price',
        'is_active',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::instance($date)->setTimezone('America/Lima')->toDateTimeString();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupon_product');
    }
}
