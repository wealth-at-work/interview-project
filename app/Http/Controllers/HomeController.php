<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use App\Repositories\MovieRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(protected BookRepository $bookRepository, protected MovieRepository $movieRepository)
    {

    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('Home',[
            'books' => $this->bookRepository->getLatest()->map(fn($book) => $book->formatForIndex()),
            'movies' => $this->movieRepository->getLatest()->map(fn($movie) => $movie->formatForIndex())
        ]);
    }
}
