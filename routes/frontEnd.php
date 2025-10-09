<?php

use App\Livewire\Forntend\Home\Index as HomeIndex;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/', HomeIndex::class)->name('home');

require __DIR__.'/auth.php';