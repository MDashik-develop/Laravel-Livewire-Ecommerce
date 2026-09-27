<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'media_id',
        'video_media_id',
        'link',
        'category_id',
        'sub_category_id',
        'product_id',
        'status',
        'position',
        'section',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function videoMedia()
    {
        return $this->belongsTo(Media::class, 'video_media_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
