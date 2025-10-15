<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function show(Movie $movie)
    {
        return Inertia::render('Movie/Show', [
            'movie' => $movie->formatForShow()
        ]);
    }
}
