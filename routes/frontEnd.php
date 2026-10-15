<?php

use App\Livewire\Forntend\Home\Index as HomeIndex;
use App\Livewire\Forntend\Product\Show as ProductShow;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/', HomeIndex::class)->name('home');
Route::get('/product/{slug}', ProductShow::class)->name('product.show');

require __DIR__.'/auth.php';

