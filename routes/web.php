<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MiscsController;
use App\Http\Controllers\MarketsController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\InventoriesController;

Route::get('/', function () {
    return view('welcome');
    // return view('auth.login');
});

Auth::routes();

Route::get('/register/supplier', function () {
    return view('auth.registerSupplier');
});

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//inventories
Route::get('/inventory', [InventoriesController::class, 'index'])->name('inventory.index');
// Route::get('/inventory/search', [InventoriesController::class, 'search'])->name('inventory.search');
Route::get('/i/createChemical', [InventoriesController::class, 'createChemical']);
Route::get('/i/c/{chemical}/edit', [InventoriesController::class, 'editChemical']);
Route::patch('/i/c/{chemical}', [InventoriesController::class, 'updateChemical']);
Route::get('/i/cp/{chemical}/edit', [InventoriesController::class, 'editChemicalProperty']);
Route::patch('/i/cp/{chemical}', [InventoriesController::class, 'updateChemicalProperty']);
Route::delete('/i/c/{chemical}', [MarketsController::class, 'deleteChemical']);
// Route::get('/i/editChemical', [InventoriesController::class, 'editChemical']);
Route::post('/i/chemical', [InventoriesController::class, 'storeChemical']);
Route::get('/i/createInventory/{chemical}', [InventoriesController::class, 'createInventory']);
Route::get('/i/addInventory/{inventory}', [InventoriesController::class, 'addInventory']);
Route::get('/i/i/{inventory}/edit', [InventoriesController::class, 'editInventory']);
Route::patch('/i/i/{inventory}', [InventoriesController::class, 'updateInventory']);
Route::delete('/i/i/{inventory}', [MarketsController::class, 'deleteInventory']);
Route::post('/i/inventory/{chemical}', [InventoriesController::class, 'storeInventory']);
Route::post('/i/addInventory/{inventory}', [InventoriesController::class, 'storeAddInventory']);
// Route::get('/i/scrape', [InventoriesController::class, 'scrape']);
Route::get('/api/registered-chemicals', [InventoriesController::class, 'autocomplete']);


// Route::get('/i/threshold', [InventoriesController::class, 'editThreshold'])->name('inventory.threshold.edit');
// Route::get('/i/threshold/{inventory}', [InventoriesController::class, 'editThreshold'])->name('inventory.threshold.edit');
// Route::patch('/i/threshold/{inventory}', [InventoriesController::class, 'storeThreshold'])->name('inventory.threshold.store');

Route::get('/i/{inventory}/unseal', [InventoriesController::class, 'unseal'])->name('inventory.unseal');
Route::get('/i/{inventory}/reduce', [InventoriesController::class, 'reduceQuantity']);
Route::post('/i/{inventory}/reduce', [InventoriesController::class, 'storeReduce']);
Route::delete('/i/{inventory}/delete', [InventoriesController::class, 'destroy'])->name('inventory.destroy');
Route::get('/i/{chemical}', [InventoriesController::class, 'details'])->name('inventory.detail');

//miscs
Route::get('/alerts/{type?}', [MiscsController::class, 'showAlerts'])->name('miscs.alert');
// Route::get('/alerts/go/{request}', [MiscsController::class, 'alertRedirect'])->name('miscs.alertRedirect');
Route::post('/alerts/{alert}/increment', [MiscsController::class, 'increment'])->name('alerts.increment');
Route::get('/alerts/{alert}/read', [MiscsController::class, 'markAsRead']);

// Route::get('/logs', [MiscsController::class, 'logs'])->name('miscs.logs');
Route::get('/logs/usage', [MiscsController::class, 'inventoryLog'])->name('miscs.inventoryLogs');
Route::get('/logs/{type?}', [MiscsController::class, 'logs'])->name('miscs.logs');

Route::get('/request', [MiscsController::class, 'createRequest']);
Route::post('/request', [MiscsController::class, 'storeRequest']);
Route::get('/request/{type}', [MiscsController::class, 'requestOption']);

Route::get('/brands', [MiscsController::class, 'indexBrands'])->name('brands.index');
Route::post('/brands', [MiscsController::class, 'storeBrands'])->name('brands.store');

Route::get('/chemistry-news', [MiscsController::class, 'showChemistryNews']);

//markets
Route::get('/market', [MarketsController::class, 'index'])->name('market.index');
// Route::get('/m/search', [MarketsController::class, 'search'])->name('market.search');
Route::get('/m/create', [MarketsController::class, 'create'])->name('market.create');
Route::get('/m/createRe', [MarketsController::class, 'createRe'])->name('market.createRe');
Route::get('/m/create/{chemical}', [MarketsController::class, 'createOption'])->name('market.createOption');
Route::post('/m/store', [MarketsController::class, 'store']);
Route::get('/m/{markets}/edit', [MarketsController::class, 'edit']);
Route::get('/m/{markets}', [MarketsController::class, 'detail'])->name('market.detail');
Route::patch('/m/{markets}', [MarketsController::class, 'update']);
Route::delete('/m/{markets}', [MarketsController::class, 'delete']);
Route::get('/m/{markets}/bid', [MarketsController::class, 'bid']);
Route::post('/m/{markets}/bid', [MarketsController::class, 'storeBid']);
Route::get('/m/{bids}/bid/edit', [MarketsController::class, 'editBid']);
Route::patch('/m/{bids}/bid', [MarketsController::class, 'updateBid']);
Route::delete('/m/{bids}/bid', [MarketsController::class, 'deleteBid']);
Route::get('/m/{bids}/bid/accept', [MarketsController::class, 'accept'])->name('market.accept');


Route::get('/cart', [MarketsController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/add/{bids}', [MarketsController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/update/{item}', [MarketsController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/{cart}/checkout', [MarketsController::class, 'checkout'])->name('cart.checkout');
Route::get('/orders', [MarketsController::class, 'orders'])->name('cart.orders');
Route::patch('/orders/{order}/respond', [MarketsController::class, 'respond'])->name('orders.respond');
Route::patch('/orders/{order}/done', [MarketsController::class, 'markDone'])->name('orders.markDone');


// Route::post('/api/paypal/webhook', [ApiController::class, 'handleWebhook'])->withoutMiddleware(['auth']);
// Route::post('/api/paypal/webhook', [MarketsController::class, 'handleWebhook']);

Route::get('/m/create/paypal/{markets}', [MarketsController::class, 'createPaypal']);
Route::post('/m/complete/{markets}', [MarketsController::class, 'complete'])->name('complete');
Route::get('/m/paypal/payout/{markets}', [MarketsController::class, 'sendPayout']);

// Route::post('/m/{markets}/checkout', [MarketsController::class, 'checkout'])->name('checkout');
// Route::get('/m/{markets}/success', [MarketsController::class, 'success'])->name('success');
// Route::get('/stripe/onboarding/refresh', fn() => redirect('/market'))->name('stripe.refresh');
// Route::get('/stripe/onboarding/return', fn() => redirect('/market'))->name('stripe.return');

// Route::get('/m/s/{markets}', [MarketsController::class, 'createStripeAccount']);

// Route::get('/m/banks/FPX', [MarketsController::class, 'getBankFPX']);
// Route::get('/m/{markets}/bill', [MarketsController::class, 'createBill']);
// Route::get('/m/bill/{bill_code}', [MarketsController::class, 'billPaymentLink'])->name('billPay');

//profiles
Route::get('/profile/{user}', [ProfilesController::class, 'details'])->name('profile.show');
Route::get('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}', [ProfilesController::class, 'update'])->name('profile.update');

//admin
Route::get('/a/users', [AdminController::class, 'viewUsers'])->name('admin.viewUsers');
Route::patch('/a/users/{id}/ban', [AdminController::class, 'toggleBan']);

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
