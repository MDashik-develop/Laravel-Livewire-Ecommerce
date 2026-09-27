<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    protected $fillable = [
        'review_id',
        'media_id',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
