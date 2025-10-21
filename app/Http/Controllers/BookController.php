<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
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
            'comments' => CommentResource::collection(
                $book->comments()->with('user')->latest()->get()
            ),
        ]);
    }
}
