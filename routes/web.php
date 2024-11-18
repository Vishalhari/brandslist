<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\backend\IndexController;
use App\Http\Controllers\backend\BrandsController;
use App\Http\Controllers\backend\ModelsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [indexController::class, 'brandslist']);
Route::get('get_allbrands', [indexController::class, 'get_allbrands']);
Route::get('allbrandsby_alphebet/{key}', [indexController::class, 'getbrandsby_aphebet']);

Route::group(['prefix' => 'admin'],function(){
    Route::get('/', [indexController::class, 'index'])->name('index');
    Route::resource('brands', BrandsController::class);
    Route::resource('models', ModelsController::class);
    Route::get('allbrands', [ModelsController::class, 'get_brands']);
});



