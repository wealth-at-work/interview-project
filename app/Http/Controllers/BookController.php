<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Inertia\Inertia;

class BookController extends Controller
{
    public function show(Book $book)
    {
        return Inertia::render('Book/Show', [
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
                'picture' => $book->cover ?? config('defaults.book_picture'),
            ],
            'comments' => $book->comments()
                ->with('user')
                ->latest()
                ->get()
                ->map(fn($comment) => $comment->formatForShow()),
        ]);
    }
}
