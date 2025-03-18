<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\InventoriesController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/inventory', [InventoriesController::class, 'index'])->name('inventory.index');
Route::get('/inventory/search', [InventoriesController::class, 'search'])->name('inventory.search');
Route::get('/i/create', [InventoriesController::class, 'create']);
Route::post('/i', [InventoriesController::class, 'store']);
// Route::get('/i/scrape', [InventoriesController::class, 'scrape']);
Route::get('/i/{inventory}', [InventoriesController::class, 'show']);

Route::get('/profile/{user}', [ProfilesController::class, 'details'])->name('profile.show');
Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');


// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
