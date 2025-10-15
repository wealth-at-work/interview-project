<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function show(Movie $movie)
    {
        //What is wrong with this code
        return Inertia::render('Movie/Show', [
            'movie' => $movie->formatForShow(),
            'comments' => $movie->comments()
                ->latest()
                ->get()
                ->map(fn($comment) => [
                    'id' => $comment->id,
                    'title' => $comment->title,
                    'body' => $comment->body,
                    'user_id' => $comment->user_id,
                    'user_name' => $comment->user->name,
                    'created_at' => $comment->created_at->toISOString(),
                ]),
        ]);
    }
}
