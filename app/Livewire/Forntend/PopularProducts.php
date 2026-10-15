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

    public function selectCategory($categoryId = null): void
    {
        $this->selectedCategory = (!empty($categoryId) && $categoryId !== 'null') ? (int) $categoryId : null;
    }

    #[On('open-quick-view')]
    public function openQuickView($id): void
    {
        $this->quickViewId = (int) $id;
    }

    public function closeQuickView(): void
    {
        $this->quickViewId = null;
    }

    public function addToCart(int $productId, int $quantity = 1): void
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user) {
            $cart = $user->carts()->where('product_id', $productId)->first();
            if ($cart) {
                $cart->increment('quantity', $quantity);
            } else {
                $user->carts()->create(['product_id' => $productId, 'quantity' => $quantity]);
            }
        } else {
            $cart = \Illuminate\Support\Facades\Session::get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = ['product_id' => $productId, 'quantity' => $quantity];
            }
            \Illuminate\Support\Facades\Session::put('cart', $cart);
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('show-toast', [
            'title'   => 'Success 🎉',
            'message' => 'Product added to cart!',
            'type'    => 'success',
        ]);
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

        $products = $productsQuery->latest()->take(10)->get();

        $quickViewProduct = null;
        if ($this->quickViewId) {
            $quickViewProduct = Product::with(['attributes', 'media', 'category', 'galleries.media'])->find($this->quickViewId);
        }

        return view('livewire.forntend.popular-products', [
            'categories'       => $categories,
            'products'         => $products,
            'quickViewProduct' => $quickViewProduct,
        ]);
    }
}
