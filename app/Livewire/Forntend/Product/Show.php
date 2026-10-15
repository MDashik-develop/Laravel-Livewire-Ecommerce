<?php

namespace App\Livewire\Forntend\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Show extends Component
{
    public Product $product;
    public int $quantity = 1;
    public string $activeTab = 'description';

    // Selected attributes (attribute_id => attribute_value_id)
    public array $selectedAttributes = [];

    // Resolved price/stock based on selected variant or product default
    public float $displayPrice  = 0;
    public ?float $displayOffer = null;
    public int $displayStock    = 0;

    public function mount(string $slug): void
    {
        $this->product = Product::with([
            'media',
            'category',
            'subCategory',
            'brand',
            'galleries.media',
            'reviews',
            'attributes',                                   // ProductAttribute (color, size, price...)
            'variants.variantAttributes.attribute',         // ProductVariant -> attributes
            'variants.variantAttributes.attributeValue',    // -> values (value, color_code)
        ])->where('slug', $slug)->where('status', true)->firstOrFail();

        $this->displayPrice  = (float) $this->product->price;
        $this->displayOffer  = $this->product->discount_price ? (float) $this->product->discount_price : null;
        $this->displayStock  = (int) $this->product->stock;

        // Auto-heal media_id from galleries if product has no main media assigned
        if (!$this->product->media && $this->product->galleries->isNotEmpty()) {
            $firstGallery = $this->product->galleries->first();
            if ($firstGallery && $firstGallery->media_id) {
                $this->product->updateQuietly(['media_id' => $firstGallery->media_id]);
                $this->product->load('media');
            }
        }
    }

    /** Called when user selects a color or size option */
    public function selectAttribute(int $attributeId, int $valueId): void
    {
        $this->selectedAttributes[$attributeId] = $valueId;
        $this->resolveVariantPrice();
    }

    /** Try to find matching variant and update price/stock */
    private function resolveVariantPrice(): void
    {
        if ($this->selectedAttributes) {
            // Find variant that has ALL selected attribute values
            $matched = $this->product->variants->first(function ($variant) {
                foreach ($this->selectedAttributes as $attrId => $valId) {
                    $has = $variant->variantAttributes
                        ->where('attribute_id', $attrId)
                        ->where('attribute_value_id', $valId)
                        ->isNotEmpty();
                    if (! $has) return false;
                }
                return true;
            });

            if ($matched) {
                $this->displayPrice  = (float) $matched->selling_price;
                $this->displayOffer  = $matched->discount_price ? (float) $matched->discount_price : null;
                $this->displayStock  = (int) $matched->stock;
                return;
            }
        }

        // Also check ProductAttribute (simple color/size rows)
        if ($this->product->attributes->isNotEmpty()) {
            $attr = $this->product->attributes->first();
            $this->displayPrice  = (float) ($attr->price ?? $this->product->price);
            $this->displayOffer  = $attr->offer_price ? (float) $attr->offer_price : null;
            $this->displayStock  = (int) ($attr->quantity ?? $this->product->stock);
        }
    }

    public function selectSimpleAttribute(int $attributeId): void
    {
        $attr = $this->product->attributes->find($attributeId);
        if ($attr) {
            $this->displayPrice  = (float) ($attr->price ?? $this->product->price);
            $this->displayOffer  = $attr->offer_price ? (float) $attr->offer_price : null;
            $this->displayStock  = (int) ($attr->quantity ?? $this->product->stock);
        }
    }

    public function incrementQty(): void
    {
        if ($this->quantity < $this->displayStock || $this->displayStock === 0) {
            $this->quantity++;
        }
    }

    public function decrementQty(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(): void
    {
        $user = Auth::user();

        if ($user) {
            $cart = $user->carts()->where('product_id', $this->product->id)->first();
            if ($cart) {
                $cart->increment('quantity', $this->quantity);
            } else {
                $user->carts()->create([
                    'product_id' => $this->product->id,
                    'quantity'   => $this->quantity,
                ]);
            }
        } else {
            $cart = Session::get('cart', []);
            if (isset($cart[$this->product->id])) {
                $cart[$this->product->id]['quantity'] += $this->quantity;
            } else {
                $cart[$this->product->id] = [
                    'product_id' => $this->product->id,
                    'quantity'   => $this->quantity,
                ];
            }
            Session::put('cart', $cart);
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('show-toast', [
            'title'   => 'Added to Cart 🎉',
            'message' => $this->product->name . ' cart-e add hoyeche!',
            'type'    => 'success',
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $relatedProducts = Product::with(['media', 'category'])
            ->where('status', true)
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->take(5)
            ->get();

        // Build grouped attributes from variants for the selector UI
        // Group: attribute_name => [attribute_id, type, values: [{value_id, value, color_code}]]
        $groupedAttributes = collect();
        if ($this->product->variants->isNotEmpty()) {
            $attrsMap = [];
            foreach ($this->product->variants as $variant) {
                foreach ($variant->variantAttributes as $va) {
                    $attr = $va->attribute;
                    $val  = $va->attributeValue;
                    if (! $attr || ! $val) continue;
                    if (! isset($attrsMap[$attr->id])) {
                        $attrsMap[$attr->id] = [
                            'id'     => $attr->id,
                            'name'   => $attr->name,
                            'type'   => $attr->type,
                            'values' => [],
                        ];
                    }
                    $attrsMap[$attr->id]['values'][$val->id] = [
                        'id'         => $val->id,
                        'value'      => $val->value,
                        'color_code' => $val->color_code,
                    ];
                }
            }
            $groupedAttributes = collect($attrsMap)->map(function ($a) {
                $a['values'] = array_values($a['values']);
                return $a;
            });
        }

        return view('livewire.forntend.product.show', [
            'relatedProducts'   => $relatedProducts,
            'groupedAttributes' => $groupedAttributes,
        ])->layout('components.layouts.app.frontend-clean');
    }
}
