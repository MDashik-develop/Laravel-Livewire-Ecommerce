<?php

namespace App\Livewire\Partials;

use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Carts extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
    //     if (Auth::check()) {
    //         // logged-in user → count from DB
    //         $this->cartCount = Auth::user()->carts()->sum('quantity');
    //     } else {
    //         // guest → count from session
    //         $cart = session()->get('cart', []);
    //         $this->cartCount = array_sum($cart);
    //     }
        if (Auth::check()) {
            // Logged-in → use database sum
            $this->cartCount = Auth::user()->carts()->sum('quantity');
    //         $this->cartCount = Auth::user()->carts()->sum('quantity');
        } else {
            // Guest → sum only the `quantity` column from session cart
            $cart = Session::get('cart', []);
            $this->cartCount = array_sum(array_column($cart, 'quantity'));
        // dd($this->cartCount);
        }

    }

    public function render()
    {
        return view('livewire.partials.carts');
    }
}
