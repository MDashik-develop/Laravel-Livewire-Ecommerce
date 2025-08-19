<?php

use App\Livewire\Backend\Categories\Index as CategoriesIndex;
use App\Livewire\Backend\Permission\Index as PermissionIndex;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/permissions', PermissionIndex::class)->name('backend.permissions.index');

Route::get('categories/', CategoriesIndex::class)->name('backend.categories.index');

require __DIR__.'/auth.php';
