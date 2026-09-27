<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'media_id',
        'sort_order',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
