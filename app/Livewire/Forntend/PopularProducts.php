<?php

namespace App\Livewire\Forntend;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class PopularProducts extends Component
{
    public ?int $selectedCategory = null;
    public ?int $quickViewId = null;

    // Quick view reactive state
    public int $quantity = 1;
    public array $selectedAttributes = [];
    public float $displayPrice = 0;
    public ?float $displayOffer = null;
    public int $displayStock = 0;

    public function selectCategory($categoryId = null): void
    {
        $this->selectedCategory = (!empty($categoryId) && $categoryId !== 'null') ? (int) $categoryId : null;
    }

    #[On('open-quick-view')]
    public function openQuickView($id): void
    {
        if (is_array($id) && isset($id['id'])) {
            $id = $id['id'];
        }
        $this->quickViewId = (int) $id;
        $this->quantity = 1;
        $this->selectedAttributes = [];

        $product = Product::with([
            'attributes',
            'media',
            'category',
            'subCategory',
            'brand',
            'galleries.media',
            'reviews',
            'variants.variantAttributes.attribute',
            'variants.variantAttributes.attributeValue',
            'variants.media',
        ])->find($this->quickViewId);

        if ($product) {
            $this->displayPrice = (float) $product->price;
            $this->displayOffer = $product->discount_price ? (float) $product->discount_price : null;
            $this->displayStock = (int) $product->stock;

            // Auto-select default variant attributes if variants exist
            if ($product->variants->isNotEmpty()) {
                $defaultVariant = $product->variants->firstWhere('is_default', true) ?? $product->variants->first();
                if ($defaultVariant) {
                    foreach ($defaultVariant->variantAttributes as $va) {
                        if ($va->attribute_id && $va->attribute_value_id) {
                            $this->selectedAttributes[$va->attribute_id] = $va->attribute_value_id;
                        }
                    }
                    $this->displayPrice = (float) ($defaultVariant->selling_price ?? $product->price);
                    $this->displayOffer = $defaultVariant->discount_price ? (float) $defaultVariant->discount_price : null;
                    $this->displayStock = (int) $defaultVariant->stock;
                }
            } elseif ($product->attributes->isNotEmpty()) {
                $firstAttr = $product->attributes->first();
                if ($firstAttr) {
                    $this->displayPrice = (float) ($firstAttr->price ?? $product->price);
                    $this->displayOffer = $firstAttr->offer_price ? (float) $firstAttr->offer_price : null;
                    $this->displayStock = (int) ($firstAttr->quantity ?? $product->stock);
                }
            }
        }
    }

    public function closeQuickView(): void
    {
        $this->quickViewId = null;
        $this->quantity = 1;
        $this->selectedAttributes = [];
    }

    public function selectAttribute(int $attributeId, int $valueId): void
    {
        $this->selectedAttributes[$attributeId] = $valueId;
        $this->resolveVariantPrice();
    }

    public function selectSimpleAttribute(int $attributeId): void
    {
        if (!$this->quickViewId) {
            return;
        }
        $product = Product::with('attributes')->find($this->quickViewId);
        if (!$product) {
            return;
        }

        $attr = $product->attributes->find($attributeId);
        if ($attr) {
            $this->displayPrice = (float) ($attr->price ?? $product->price);
            $this->displayOffer = $attr->offer_price ? (float) $attr->offer_price : null;
            $this->displayStock = (int) ($attr->quantity ?? $product->stock);
        }
    }

    private function resolveVariantPrice(): void
    {
        if (!$this->quickViewId) {
            return;
        }

        $product = Product::with([
            'attributes',
            'variants.variantAttributes',
        ])->find($this->quickViewId);

        if (!$product) {
            return;
        }

        if ($this->selectedAttributes && $product->variants->isNotEmpty()) {
            $matched = $product->variants->first(function ($variant) {
                foreach ($this->selectedAttributes as $attrId => $valId) {
                    $has = $variant->variantAttributes
                        ->where('attribute_id', $attrId)
                        ->where('attribute_value_id', $valId)
                        ->isNotEmpty();
                    if (!$has) {
                        return false;
                    }
                }
                return true;
            });

            if ($matched) {
                $this->displayPrice = (float) $matched->selling_price;
                $this->displayOffer = $matched->discount_price ? (float) $matched->discount_price : null;
                $this->displayStock = (int) $matched->stock;
                return;
            }
        }

        if ($product->attributes->isNotEmpty()) {
            $attr = $product->attributes->first();
            $this->displayPrice = (float) ($attr->price ?? $product->price);
            $this->displayOffer = $attr->offer_price ? (float) $attr->offer_price : null;
            $this->displayStock = (int) ($attr->quantity ?? $product->stock);
        } else {
            $this->displayPrice = (float) $product->price;
            $this->displayOffer = $product->discount_price ? (float) $product->discount_price : null;
            $this->displayStock = (int) $product->stock;
        }
    }

    public function incrementQty(): void
    {
        if ($this->displayStock <= 0 || $this->quantity < $this->displayStock) {
            $this->quantity++;
        }
    }

    public function decrementQty(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(int $productId, ?int $quantity = null): void
    {
        $qty = $quantity ?? $this->quantity;
        if ($qty <= 0) {
            $qty = 1;
        }

        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user) {
            $cart = $user->carts()->where('product_id', $productId)->first();
            if ($cart) {
                $cart->increment('quantity', $qty);
            } else {
                $user->carts()->create(['product_id' => $productId, 'quantity' => $qty]);
            }
        } else {
            $cart = \Illuminate\Support\Facades\Session::get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $qty;
            } else {
                $cart[$productId] = ['product_id' => $productId, 'quantity' => $qty];
            }
            \Illuminate\Support\Facades\Session::put('cart', $cart);
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('show-toast', [
            'title'   => 'Added to Cart 🎉',
            'message' => 'Product added to cart!',
            'type'    => 'success',
        ]);
    }

    public function buyNow(int $productId): void
    {
        $this->addToCart($productId, $this->quantity);
        $this->closeQuickView();

        if (\Illuminate\Support\Facades\Route::has('checkout')) {
            $this->redirectRoute('checkout');
        } elseif (\Illuminate\Support\Facades\Route::has('cart')) {
            $this->redirectRoute('cart');
        }
    }

    public function render()
    {
        $categories = Category::where('status', true)
            ->whereHas('products', fn($q) => $q->where('status', true))
            ->withCount(['products' => fn($q) => $q->where('status', true)])
            ->orderBy('name')
            ->get();

        $productsQuery = Product::with(['attributes', 'media', 'category', 'galleries.media'])
            ->where('status', true);

        if ($this->selectedCategory !== null) {
            $productsQuery->where('category_id', $this->selectedCategory);
        }

        $products = $productsQuery->latest()->take(12)->get();

        $quickViewProduct = null;
        $groupedAttributes = collect();

        if ($this->quickViewId) {
            $quickViewProduct = Product::with([
                'attributes',
                'media',
                'category',
                'subCategory',
                'brand',
                'galleries.media',
                'reviews',
                'variants.variantAttributes.attribute',
                'variants.variantAttributes.attributeValue',
                'variants.media',
            ])->find($this->quickViewId);

            if ($quickViewProduct && $quickViewProduct->variants->isNotEmpty()) {
                $attrsMap = [];
                foreach ($quickViewProduct->variants as $variant) {
                    foreach ($variant->variantAttributes as $va) {
                        $attr = $va->attribute;
                        $val  = $va->attributeValue;
                        if (!$attr || !$val || trim((string)$val->value) === '') continue;

                        if (!isset($attrsMap[$attr->id])) {
                            $attrsMap[$attr->id] = [
                                'id'     => $attr->id,
                                'name'   => $attr->name,
                                'type'   => $attr->type,
                                'values' => [],
                            ];
                        }

                        $attrsMap[$attr->id]['values'][$val->id] = [
                            'id'         => $val->id,
                            'value'      => trim((string)$val->value),
                            'color_code' => $val->color_code,
                            'media_url'  => $va->media?->url ?? $variant->media?->url,
                        ];
                    }
                }

                $groupedAttributes = collect($attrsMap)
                    ->filter(fn($a) => !empty($a['values']))
                    ->map(function ($a) {
                        $a['values'] = array_values($a['values']);
                        return $a;
                    });
            }
        }

        return view('livewire.forntend.popular-products', [
            'categories'        => $categories,
            'products'          => $products,
            'quickViewProduct'  => $quickViewProduct,
            'groupedAttributes' => $groupedAttributes,
        ]);
    }
}
