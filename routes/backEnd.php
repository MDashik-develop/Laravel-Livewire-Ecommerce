<?php

use App\Livewire\Backend\Categories\Index as CategoriesIndex;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('categories/', CategoriesIndex::class)->name('backend.categories.index');

require __DIR__.'/auth.php';
