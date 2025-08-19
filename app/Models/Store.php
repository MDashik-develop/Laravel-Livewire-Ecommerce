<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    protected $fillable = [
        'name', 'slug', 'description', 'logo', 'phone', 'address', 'is_approved', 'status'
    ];
}
