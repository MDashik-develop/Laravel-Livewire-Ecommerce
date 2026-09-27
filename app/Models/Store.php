<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'media_id',
        'fav_media_id',
        'og_media_id',
        'phone',
        'email',
        'address',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'sitemap',
        'twitter_title',
        'twitter_description',
        'meta_pixel_id',
        'google_ads_id',
        'google_analytics_id',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'about_page',
        'contact_page',
        'privacy_page',
        'return_page',
        'terms_page',
        'is_approved',
        'status',
        'user_id',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function favMedia()
    {
        return $this->belongsTo(Media::class, 'fav_media_id');
    }

    public function ogMedia()
    {
        return $this->belongsTo(Media::class, 'og_media_id');
    }
}
