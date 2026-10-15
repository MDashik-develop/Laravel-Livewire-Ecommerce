<?php

namespace App\Livewire\Layouts\Frontend;

use App\Models\Attribute;
use App\Models\Product;
use Livewire\Component;

class AsideFilter extends Component
{
    public float $minPrice = 0;
    public float $maxPrice = 1000;
    public float $currentPrice = 500;
    public array $colors = [];

    public function mount(): void
    {
        $min = Product::where('status', true)->min('price');
        $max = Product::where('status', true)->max('price');

        $this->minPrice = $min !== null ? (float) $min : 0;
        $this->maxPrice = $max !== null ? (float) $max : 1000;

        if ($this->maxPrice <= $this->minPrice) {
            $this->maxPrice = $this->minPrice + 500;
        }

        $this->currentPrice = $this->maxPrice;

        // Fetch colors from attributes if available
        $colorAttr = Attribute::with(['values'])->where('name', 'like', '%color%')->first();
        if ($colorAttr && $colorAttr->values->isNotEmpty()) {
            $this->colors = $colorAttr->values->take(6)->map(function ($val) {
                return [
                    'id'         => $val->id,
                    'name'       => $val->value,
                    'color_code' => $val->color_code,
                    'count'      => rand(10, 80),
                ];
            })->toArray();
        } else {
            $this->colors = [
                ['id' => 1, 'name' => 'Red', 'color_code' => '#ef4444', 'count' => 56],
                ['id' => 2, 'name' => 'Green', 'color_code' => '#10b981', 'count' => 78],
                ['id' => 3, 'name' => 'Blue', 'color_code' => '#3b82f6', 'count' => 54],
            ];
        }
    }

    public function render()
    {
        return view('livewire.layouts.frontend.aside-filter');
    }
}
