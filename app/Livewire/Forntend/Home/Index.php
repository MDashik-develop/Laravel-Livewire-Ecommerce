<?php

namespace App\Livewire\Forntend\Home;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Index extends Component
{
    public function addToCart($productId)
    {
        $user = Auth::user();

        if ($user) {
            $cart = $user->carts()->where('product_id', $productId)->first();

            if ($cart) {
                $cart->increment('quantity');
            } else {
                $user->carts()->create([
                    'product_id' => $productId,
                    'quantity' => 1,
                ]);
            }
        } else {
            $cart = Session::get('cart', []);

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity']++;
            } else {
                $cart[$productId] = [
                    'product_id' => $productId,
                    'quantity' => 1,
                ];
            }

            Session::put('cart', $cart);
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('show-toast', [
            'title'   => 'Success 🎉',
            'message' => 'Saved successfully!',
            'type'    => 'success',
        ]);
    }

    public function render()
    {
        $sliderBanners = Banner::with(['media', 'videoMedia', 'category', 'product'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('section', 'slider')
                  ->orWhereNull('section')
                  ->orWhere('section', '');
            })
            ->orderBy('position', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $featuredBanners = Banner::with(['media', 'videoMedia', 'category', 'product'])
            ->where('status', true)
            ->where('section', 'featured')
            ->orderBy('position', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $footerBanners = Banner::with(['media', 'videoMedia', 'category', 'product'])
            ->where('status', true)
            ->where('section', 'footer')
            ->orderBy('position', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $products = Product::with(['attributes', 'media'])
            ->where('status', true)
            ->latest()
            ->take(16)
            ->get();

        return view('livewire.forntend.home.index', [
            'banners'         => $sliderBanners,
            'sliderBanners'   => $sliderBanners,
            'featuredBanners' => $featuredBanners,
            'footerBanners'   => $footerBanners,
            'products'        => $products,
        ]);
    }
}
