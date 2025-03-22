<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\InventoriesController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/i/usage', [InventoriesController::class, 'inventoryLog']);

Route::get('/inventory', [InventoriesController::class, 'index'])->name('inventory.index');
Route::get('/inventory/search', [InventoriesController::class, 'search'])->name('inventory.search');
Route::get('/i/createChemical', [InventoriesController::class, 'createChemical']);
Route::post('/i/chemical', [InventoriesController::class, 'storeChemical']);
Route::get('/i/createInventory', [InventoriesController::class, 'createInventory']);
Route::post('/i/inventory', [InventoriesController::class, 'storeInventory']);
// Route::get('/i/scrape', [InventoriesController::class, 'scrape']);

Route::get('/i/alerts', [InventoriesController::class, 'showAlerts']);
Route::get('/i/alerts/{alert}/read', [InventoriesController::class, 'markAsRead']);

Route::get('/i/{inventory}/unseal', [InventoriesController::class, 'unseal'])->name('inventory.unseal');
Route::get('/i/{inventory}/reduce', [InventoriesController::class, 'reduceQuantity']);
Route::post('/i/{inventory}/reduce', [InventoriesController::class, 'storeReduce']);
Route::get('/i/{chemical}', [InventoriesController::class, 'show']);

Route::get('/profile/{user}', [ProfilesController::class, 'details'])->name('profile.show');
Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');


// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
