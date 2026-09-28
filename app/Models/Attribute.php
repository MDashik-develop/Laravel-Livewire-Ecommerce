<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type', // text, color, image
        'is_filterable',
        'sort_order',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'sort_order'    => 'integer',
    ];

    public function values()
    {
        return $this->hasMany(AttributeValue::class)->orderBy('sort_order', 'asc');
    }

    public function variantAttributes()
    {
        return $this->hasMany(ProductVariantAttribute::class);
    }
}
