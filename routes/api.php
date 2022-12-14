<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('data/smaregi', [\App\Http\Controllers\DataController::class, 'get']);

Route::post('data/export', [\App\Http\Controllers\DataController::class, 'exportCsv'])->name('export-csv');

Route::get('dproducts', function () {
    $market = \App\Models\Market::all()->first();
    $rows = $market->dproducts;
    return response()->json(json_decode($rows->first()->data));
});

//Route::post('/data/import', [\App\Http\Controllers\DataController::class, 'import'])->name('data-import');
