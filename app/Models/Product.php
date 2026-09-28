<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'brand_id',
        'name',
        'slug',
        'has_variants',
        'sku',
        'price',
        'cost_price',
        'discount_price',
        'stock',
        'barcode',
        'weight',
        'short_description',
        'long_description',
        'media_id',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'has_variants'   => 'boolean',
        'is_featured'     => 'boolean',
        'status'          => 'boolean',
        'price'           => 'decimal:2',
        'cost_price'      => 'decimal:2',
        'discount_price'  => 'decimal:2',
        'stock'           => 'integer',
        'weight'          => 'decimal:2',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class)->orderBy('sort_order', 'asc');
    }

    public function galleryMedia()
    {
        return $this->hasManyThrough(Media::class, ProductGallery::class, 'product_id', 'id', 'id', 'media_id');
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class)->latest('id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Backward compatibility relations
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function gallery()
    {
        return $this->hasManyThrough(Media::class, ProductImage::class, 'product_id', 'id', 'id', 'media_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    public function getEffectivePriceAttribute(): ?float
    {
        return $this->discount_price ?? $this->price;
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }
}
