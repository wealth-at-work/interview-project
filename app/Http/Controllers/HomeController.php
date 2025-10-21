<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use App\Repositories\MovieRepository;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(protected BookRepository $bookRepository, protected MovieRepository $movieRepository) {}

    public function index(): \Inertia\Response
    {
        return Inertia::render('Home', [
            'books' => $this->bookRepository->getLatest(4)->map(fn ($book) => $book->formatForIndex()),
            'movies' => $this->movieRepository->getLatest(4)->map(fn ($movie) => $movie->formatForIndex()),
        ]);
    }
}
