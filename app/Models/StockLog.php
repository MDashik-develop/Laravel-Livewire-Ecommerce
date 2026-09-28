<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'user_id',
        'type', // initial, manual_adjustment, sale, return, restock, damage
        'quantity_change',
        'old_stock',
        'new_stock',
        'reason',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'quantity_change' => 'integer',
        'old_stock'       => 'integer',
        'new_stock'       => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a stock activity log entry.
     */
    public static function record(
        int $productId,
        ?int $variantId,
        int $quantityChange,
        int $oldStock,
        int $newStock,
        string $type = 'manual_adjustment',
        ?string $reason = null,
        ?Model $reference = null
    ): self {
        return self::create([
            'product_id'         => $productId,
            'product_variant_id' => $variantId,
            'user_id'            => Auth::id(),
            'type'               => $type,
            'quantity_change'    => $quantityChange,
            'old_stock'          => $oldStock,
            'new_stock'          => $newStock,
            'reason'             => $reason,
            'reference_type'     => $reference ? get_class($reference) : null,
            'reference_id'       => $reference ? $reference->getKey() : null,
        ]);
    }
}
