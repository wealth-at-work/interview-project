<?php

namespace App\Models;

use App\HasExistingRecord;
use App\Services\Interfaces\MovieLookup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

class Movie extends UploadableModel
{
    use HasExistingRecord,
        HasFactory;

    protected $fillable = [
        'title',
        'poster',
        'added_by',
    ];

    public function formatForIndex(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'picture' => strlen($this->poster) > 0 ? $this->poster : config('defaults.movie_picture'),
            'added_by' => $this->uploader->name,
        ];
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function formatForShow(): array
    {
        $cacheKey = "movie_details_{$this->id}";

        // Try to get from cache first
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        // Fetch fresh data
        $movieDetails = app(MovieLookup::class)->getMovieByName($this->title);

        // If API fails, return minimal data without caching
        if (! $movieDetails) {
            return [
                'id' => $this->id,
                'title' => $this->title,
                'synopsis' => null,
                'ratings' => [
                    'imdb' => ['score' => null, 'votes' => null],
                    'metacritic' => ['score' => null],
                    'rotten_tomatoes' => ['score' => null],
                ],
                'poster' => $this->poster ?? config('defaults.movie_picture'),
                'director' => null,
                'writer' => null,
                'actors' => [],
            ];
        }

        // Format successful response
        $formatted = [
            'id' => $this->id,
            'title' => $this->title,
            'synopsis' => $movieDetails['Plot'] ?? null,
            'ratings' => [
                'imdb' => [
                    'score' => $movieDetails['imdbRating'] ?? null,
                    'votes' => $movieDetails['imdbVotes'] ?? null,
                ],
                'metacritic' => [
                    'score' => isset($movieDetails['Metascore']) && $movieDetails['Metascore'] !== 'N/A'
                        ? $movieDetails['Metascore'].'/100'
                        : null,
                ],
                'rotten_tomatoes' => [
                    'score' => collect($movieDetails['Ratings'] ?? [])->firstWhere('Source', 'Rotten Tomatoes')['Value'] ?? null,
                ],
            ],
            'poster' => $this->poster ?? $movieDetails['Poster'] ?? config('defaults.movie_picture'),
            'director' => $movieDetails['Director'] !== 'N/A' ? $movieDetails['Director'] : null,
            'writer' => $movieDetails['Writer'] !== 'N/A' ? $movieDetails['Writer'] : null,
            'actors' => isset($movieDetails['Actors']) && $movieDetails['Actors'] !== 'N/A'
                ? explode(', ', $movieDetails['Actors'])
                : [],
        ];

        // Cache only successful responses
        Cache::put($cacheKey, $formatted, now()->addHour());

        return $formatted;
    }
}
