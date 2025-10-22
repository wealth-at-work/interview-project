<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\FavoriteController;

// Redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/book/{book}', [BookController::class, 'show'])->name('show_book');
    Route::get('/movie/{movie}', [MovieController::class, 'show'])->name('show_movie');

    //routes for favorites
    Route::prefix('favorites')->name('favorites.')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
        Route::post('/', [FavoriteController::class, 'store'])->name('store');
        Route::delete('/', [FavoriteController::class, 'destroy'])->name('destroy');
        Route::post('/toggle', [FavoriteController::class, 'toggle'])->name('toggle');
        Route::get('/check', [FavoriteController::class, 'check'])->name('check');
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
