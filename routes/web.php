<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::resource('/item', ItemController::class);
    Route::resource('/category', CategoryController::class);

    Route::patch('/item/{item}/in', [ItemController::class, 'in'])->name('item.in');
    Route::patch('/item/{item}/out', [ItemController::class, 'out'])->name('item.out');
    Route::delete('/item/{item}', [ItemController::class, 'destroy'])->name('item.destroy');
    Route::delete('/item', [ItemController::class, 'destroyOut'])->name('item.deleteallout');

    Route::middleware('admin')->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::patch('/user/{user}/makeadmin', [UserController::class, 'makeadmin'])->name('user.makeadmin');
        Route::patch('/user/{user}/removeadmin', [UserController::class, 'removeadmin'])->name('user.removeadmin');
    });
});

Route::get('/auth/github', [GithubController::class, 'redirectToProvider'])-> name('github.redirect');
Route::get('/auth/github/callback', [GithubController::class, 'handleProviderCallback'])-> name('github.callback');

require __DIR__.'/auth.php';
