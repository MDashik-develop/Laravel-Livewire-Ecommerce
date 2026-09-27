<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
        'media_id',
        'banner_media_id',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function bannerMedia()
    {
        return $this->belongsTo(Media::class, 'banner_media_id');
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
}
