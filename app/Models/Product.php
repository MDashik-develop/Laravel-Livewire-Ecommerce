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
        'short_description',
        'long_description',
        'media_id',
        'is_featured',
        'status',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function gallery()
    {
        return $this->hasManyThrough(Media::class, ProductImage::class, 'product_id', 'id', 'id', 'media_id');
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

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
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
}
