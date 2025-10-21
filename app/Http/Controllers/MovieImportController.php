<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\Interfaces\MovieLookup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class MovieImportController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $movies = [];
        $searchTerm = $request->get('q');

        if ($searchTerm) {
            $movies = app(MovieLookup::class)->getMoviesByName($searchTerm);
        }

        return Inertia::render('Movie/Add', [
            'searchTerm' => $searchTerm,
            'movies' => $movies,
        ]);
    }

    public function store(string $remoteId): RedirectResponse
    {
        if (empty($remoteId)) {
            return redirect()->route('movies.add')
                ->with('error', 'Invalid movie ID provided.');
        }

        // Fetch movie details
        $movieDetails = app(MovieLookup::class)->getMovieByRemoteId($remoteId);

        if (!$movieDetails) {
            return redirect()->route('movies.add')
                ->with('error', 'Movie not found. Please try searching again.');
        }

        $title = $movieDetails['title'] ?? '';

        // Validate title exists
        if (empty($title)) {
            return redirect()->route('movies.add')
                ->with('error', 'Movie data is incomplete.');
        }

        // Check for duplicates
        if (Movie::doesRecordAlreadyExists(title: $title)) {
            return redirect()->route('movie_list')
                ->with('error', "'{$title}' already exists in your library.");
        }

        try {
            Movie::create([
                'title' => $title,
                'poster' => $movieDetails['picture'] ?? null,
                'added_by' => 1, // As there is no current login, I have manually set the user ID
            ]);

            return redirect()->route('movie_list')
                ->with('success', "Successfully added '{$title}' to your library.");
        } catch (\Exception $e) {
            Log::error('Failed to create movie', [
                'remote_id' => $remoteId,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('movies.add')
                ->with('error', 'Failed to add movie. Please try again.');
        }
    }
}
