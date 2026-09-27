<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'media_id',
        'banner_media_id',
        'status',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function bannerMedia()
    {
        return $this->belongsTo(Media::class, 'banner_media_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
