<?php

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('product', function() {
    return Inertia::render('Product');
})->middleware(['auth', 'verified'])->name('product');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
