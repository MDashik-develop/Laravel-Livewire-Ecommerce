<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;

class MergeSessionCartIntoDatabase
{
    public function handle(Login $event)
    {
        $user = $event->user;
        $sessionCart = Session::get('cart', []);

        foreach ($sessionCart as $item) {
            $cartItem = $user->carts()->where('product_id', $item['product_id'])->first();

            if ($cartItem) {
                $cartItem->quantity += $item['quantity'];
                $cartItem->save();
            } else {
                $user->carts()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        // Session cart clear koro
        Session::forget('cart');
    }
}
