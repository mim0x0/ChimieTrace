<?php

use App\Models\ChemicalProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/paypal/webhook', [ApiController::class, 'handleWebhook']);

Route::get('/chemical-properties/csv-content', function() {
    $csv = ChemicalProperty::exportToCsv();
    return response()->json(['csvContent' => $csv]);
});
