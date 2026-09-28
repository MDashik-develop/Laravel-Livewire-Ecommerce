<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $table = 'shipping_methods';

    protected $fillable = [
        'name',
        'code',
        'base_charge',
        'per_kg_charge',
        'status',
        'cod_available',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'base_charge' => 'decimal:2',
            'per_kg_charge' => 'decimal:2',
            'status' => 'boolean',
            'cod_available' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include active shipping methods.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * Calculate shipping charge based on weight in kg.
     */
    public function calculateShippingCost(float $weight = 0): float
    {
        $base = (float) $this->base_charge;
        $perKg = (float) $this->per_kg_charge;
        $weightCost = max(0, $weight) * $perKg;

        return round($base + $weightCost, 2);
    }
}
