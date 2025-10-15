<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MovieController;

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/book/{book}', [BookController::class,'show'])->name('show_book');
Route::get('/movie/{movie}', [MovieController::class,'show'])->name('show_movie');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
