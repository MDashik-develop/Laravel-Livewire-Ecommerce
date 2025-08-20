<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
protected $fillable = ['name', 'slug', 'description', 'logo', 'phone', 'address', 'is_approved', 'status', 'user_id'];
    protected $casts = [
        'is_approved' => 'boolean',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
