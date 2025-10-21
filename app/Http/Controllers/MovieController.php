<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function index()
    {
        return Inertia::render('Movie/List', [
            'movies' => Movie::all()->map(fn ($movie) => $movie->formatForIndex()),
        ]);
    }

    public function show(Movie $movie)
    {
        return Inertia::render('Movie/Show', [
            'movie' => $movie->formatForShow(),
            'comments' => $movie->comments()
                ->with('user')// fixed!
                ->latest()
                ->get()
                ->map(fn ($comment) => $comment->formatForShow()),
        ]);
    }
}
