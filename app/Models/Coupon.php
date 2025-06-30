<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
        'is_active'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    public function isValidForProduct(Product $product): bool
    {
        // Verificar si el cupón está asignado al producto
        $isForProduct = $this->products->contains($product->id);
        
        // Verificar estado y fechas de vigencia
        $isActive = $this->is_active;
        $isDateValid = now()->between($this->valid_from, $this->valid_until);
        
        // Verificar límite de usos
        $usageValid = ($this->usage_limit === null) || ($this->used_count < $this->usage_limit);
        
        return $isForProduct && $isActive && $isDateValid && $usageValid;
    }
    
    public function calculateDiscount($price): float
    {
        return $this->discount_type === 'percentage' 
            ? $price * (1 - ($this->discount_value / 100))
            : max(0, $price - $this->discount_value);
    }
}