<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'cost_price',
        'selling_price',
        'discount_price',
        'stock',
        'weight',
        'media_id',
        'is_default',
        'status',
    ];

    protected $casts = [
        'cost_price'     => 'decimal:2',
        'selling_price'  => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock'          => 'integer',
        'weight'         => 'decimal:2',
        'is_default'     => 'boolean',
        'status'         => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($variant) {
            // Log initial stock creation if stock > 0
            if ($variant->stock > 0) {
                StockLog::create([
                    'product_id'         => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'user_id'            => Auth::id(),
                    'type'               => 'initial',
                    'quantity_change'    => $variant->stock,
                    'old_stock'          => 0,
                    'new_stock'          => $variant->stock,
                    'reason'             => $variant->is_default ? 'Initial stock for default product' : 'Initial stock for variant',
                ]);
            }
            $variant->syncProductStock();
        });

        static::updating(function ($variant) {
            // Automatically log stock changes
            if ($variant->isDirty('stock')) {
                $oldStock = (int) $variant->getOriginal('stock');
                $newStock = (int) $variant->stock;
                $change = $newStock - $oldStock;

                StockLog::create([
                    'product_id'         => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'user_id'            => Auth::id(),
                    'type'               => 'manual_adjustment',
                    'quantity_change'    => $change,
                    'old_stock'          => $oldStock,
                    'new_stock'          => $newStock,
                    'reason'             => 'Stock updated via product management',
                ]);
            }
        });

        static::updated(function ($variant) {
            if ($variant->wasChanged('stock') || $variant->wasChanged('selling_price') || $variant->wasChanged('discount_price')) {
                $variant->syncProductStock();
            }
        });

        static::deleted(function ($variant) {
            $variant->syncProductStock();
        });
    }

    /**
     * Synchronize total stock and base price to the parent product.
     */
    public function syncProductStock(): void
    {
        $product = $this->product;
        if (!$product) return;

        $totalStock = (int) $product->variants()->sum('stock');
        $minPrice = $product->variants()->min('selling_price');
        $minDiscount = $product->variants()->whereNotNull('discount_price')->min('discount_price');

        $product->updateQuietly([
            'stock'          => $totalStock,
            'price'          => $minPrice ?? $product->price,
            'discount_price' => $minDiscount ?? $product->discount_price,
        ]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function variantAttributes()
    {
        return $this->hasMany(ProductVariantAttribute::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class)->latest('id');
    }

    /**
     * Get a human-readable summary of the attributes for this variant (e.g. "Color: Red, Size: L").
     */
    public function getAttributeSummaryAttribute(): string
    {
        if ($this->is_default) {
            return 'Default';
        }

        $parts = [];
        foreach ($this->variantAttributes as $va) {
            $attrName = $va->attribute?->name;
            $valName = $va->attributeValue?->value;
            if ($attrName && $valName) {
                $parts[] = "{$attrName}: {$valName}";
            }
        }

        return !empty($parts) ? implode(', ', $parts) : ($this->sku ?: 'Variant #' . $this->id);
    }

    /**
     * Resolve display media for this variant following priority:
     * 1. Variant's own media (if set)
     * 2. Color-way attribute media (or any variant attribute media)
     * 3. Parent product's main media
     */
    public function getResolvedMediaAttribute(): ?Media
    {
        if ($this->media) {
            return $this->media;
        }

        foreach ($this->variantAttributes as $va) {
            if ($va->media) {
                return $va->media;
            }
            if ($va->attributeValue?->media) {
                return $va->attributeValue->media;
            }
        }

        return $this->product?->media;
    }
}
