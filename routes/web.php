<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/book/{book}', [BookController::class, 'show'])->name('show_book');
Route::get('/movie/{movie}', [MovieController::class, 'show'])->name('show_movie');
Route::get('/movies', [MovieController::class, 'index'])->name('movie_list');
Route::get('/movies/add', [MovieImportController::class, 'index'])->name('movies.add');
Route::get('/movies/add/{id}', [MovieImportController::class, 'store'])->name('movies.store');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
