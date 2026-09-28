<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'sandbox_mode',
        'credentials',
        'instruction',
        'media_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sandbox_mode' => 'boolean',
            'credentials' => 'array',
        ];
    }

    /**
     * Get the media logo/icon associated with this payment method.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Scope a query to only include active payment methods.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Get a specific credential value by key.
     */
    public function getCredential(string $key, mixed $default = null): mixed
    {
        return $this->credentials[$key] ?? $default;
    }
}
