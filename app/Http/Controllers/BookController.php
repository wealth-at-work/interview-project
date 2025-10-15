<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;

class BookController extends Controller
{
    public function show(Book $book)
    {
        return Inertia::render('Movie/Show', [
            'movie' => $book->formatForShow()
        ]);
    }
}
