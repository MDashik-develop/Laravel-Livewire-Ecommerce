<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
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
