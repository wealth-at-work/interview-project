<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Movie;
use App\Services\Interfaces\MovieLookup;
use Illuminate\Support\Facades\Http;

class OmdbLookup implements MovieLookup
{
    private string $baseUrl;

    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.omdb.base_url');
        $this->apiKey = config('services.omdb.api_key');
    }

    public function getMovieByName(string $name): array
    {

        $response = Http::get($this->baseUrl, [
            'apikey' => $this->apiKey,
            't' => $name,
            'plot' => 'full',
        ]);

        if (! $response->successful() || $response->json('Response') === 'False') {
            return [];
        }

        return $response->json();
    }

    public function getMoviesByName(string $name): array
    {
        $response = Http::get($this->baseUrl, [
            'apikey' => $this->apiKey,
            's' => $name,
            'plot' => 'full',
        ]);

        if (! $response->successful() || $response->json('Response') === 'False') {
            return [];
        }

        return collect($response->json()['Search'])->map(fn ($movie) => [
            'id' => $movie['imdbID'] ?? null,
            'title' => $movie['Title'] ?? null,
            'year' => $movie['Year'] ?? null,
            'picture' => $movie['Poster'] !== 'N/A' ? $movie['Poster'] : config('defaults.movie_picture'),
            'is_already_added' => Movie::doesRecordAlreadyExists(title: $movie['Title']),
        ])->toArray();
    }

    public function getMovieByRemoteId(string $remoteId): array
    {
        $response = Http::get($this->baseUrl, [
            'apikey' => $this->apiKey,
            'i' => $remoteId,
            'plot' => 'full',
        ]);

        if (! $response->successful() || $response->json('Response') === 'False') {
            return [];
        }

        $movie = $response->json();

        return [
            'id' => $remoteId,
            'title' => $movie['Title'] ?? null,
            'picture' => $movie['Poster'] ?? null,
        ];
    }
}
