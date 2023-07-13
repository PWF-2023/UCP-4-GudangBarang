<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class,'index'])->name('profile');
    // Route::resource('/item', ItemController::class);
    Route::apiResource('/item', ItemController::class);
    // Route::apiResource('/category', CategoryController::class);
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::patch('/item/{item}/in', [ItemController::class, 'in'])->name('item.in');
    Route::patch('/item/{item}/out', [ItemController::class, 'out'])->name('item.out');
    Route::delete('/item', [ItemController::class, 'deleteAllCompleted'])->name('item.deleteallcompleted');
});
