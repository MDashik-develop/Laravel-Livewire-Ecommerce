<?php

namespace App\Livewire\Partials;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Products extends Component
{
    public $product;
    public function placeholder()
    {
        return <<<'HTML'
            <div class="animate-pulse">
                <div class="bg-white rounded-2xl shadow p-5 w-full max-w-sm mx-auto">
                    
                    <!-- Image skeleton -->
                    <div class="w-full h-48 bg-gray-200 rounded-xl mb-6"></div>
                    
                    <!-- Title skeleton -->
                    <div class="h-6 bg-gray-200 rounded-md w-3/4 mx-auto mb-4"></div>
                    
                    <!-- Subtitle skeleton -->
                    <div class="h-4 bg-gray-200 rounded-md w-1/2 mx-auto mb-6"></div>
                    
                    <!-- Button skeleton -->
                    <div class="h-12 bg-gray-200 rounded-lg w-full"></div>
                </div>
            </div>
        HTML;
    }
    

    public function addToCart($productId)
    {
        $user = Auth::user();

        if ($user) {
            // Logged-in user → use database
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
            // Guest → use session
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
        return view('livewire.partials.products');
    }
}
