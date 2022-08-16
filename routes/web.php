<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('home');
});

Route::get('users', [\App\Http\Controllers\UserController::class, 'get'])->name('users');
Route::post('users', [\App\Http\Controllers\UserController::class, 'search'])->name('search-users');

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/users/create', [App\Http\Controllers\UserCreateController::class, 'post'])->name('create-user');
Route::get('/users/create', [App\Http\Controllers\UserCreateController::class, 'get'])->name('view-create-user');

Route::post('/data/import', [\App\Http\Controllers\DataController::class, 'import'])->name('data-import');
