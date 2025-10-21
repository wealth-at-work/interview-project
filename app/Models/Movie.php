<?php

namespace App\Models;

use App\Services\Interfaces\MovieLookup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Movie extends UploadableModel
{

    public function formatForIndex(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'picture' => strlen($this->poster) > 0 ? $this->poster : config('defaults.movie_picture'),
            'added_by' => $this->uploader->name
        ];
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function formatForShow(): array
    {
        return Cache::remember(
            "movie_details_{$this->id}",
            now()->addHour(),
            function () {
                $movieDetails = app(MovieLookup::class)->getMovieByName($this->title);

                $apiPoster = ($movieDetails['Poster'] ?? null);
                $apiPoster = ($apiPoster && $apiPoster !== 'N/A') ? $apiPoster : null;

                return [
                    'id' => $this->id,
                    'title' => $this->title,
                    'synopsis' => ($movieDetails['Plot'] ?? null) !== 'N/A' ? $movieDetails['Plot'] : null,
                    'ratings' => [
                        'imdb' => [
                            'score' => ($movieDetails['imdbRating'] ?? null) !== 'N/A' ? $movieDetails['imdbRating'] : null,
                            'votes' => ($movieDetails['imdbVotes'] ?? null) !== 'N/A' ? $movieDetails['imdbVotes'] : null,
                        ],
                        'metacritic' => [
                            'score' => isset($movieDetails['Metascore']) && $movieDetails['Metascore'] !== 'N/A'
                                ? $movieDetails['Metascore'] . '/100'
                                : null,
                        ],
                        'rotten_tomatoes' => [
                            'score' => collect($movieDetails['Ratings'] ?? [])->firstWhere('Source', 'Rotten Tomatoes')['Value'] ?? null,
                        ],
                    ],
                    'poster' => $this->poster ?: ($apiPoster ?: config('defaults.movie_picture')),
                    'director' => ($movieDetails['Director'] ?? null) !== 'N/A' ? $movieDetails['Director'] : null,
                    'writer' => ($movieDetails['Writer'] ?? null) !== 'N/A' ? $movieDetails['Writer'] : null,
                    'actors' => isset($movieDetails['Actors']) && $movieDetails['Actors'] !== 'N/A'
                        ? explode(', ', $movieDetails['Actors'])
                        : [],
                ];
            }
        );
    }

    public static function findSimilar(Movie $target, int $limit = 6)
    {
        $list = function (?string $csv): array {
            if (!$csv) return [];
            return collect(explode(',', $csv))
                ->map(fn($s) => trim(Str::lower($s)))
                ->filter()->unique()->values()->all();
        };

        $tokens = function (string $title) {
            static $stop = ['the','a','an','and','or','of','part'];
            return collect(preg_split('/[\s:\-]+/u', Str::lower($title)))
                ->map(fn($t) => trim($t))
                ->filter(fn($t) => strlen($t) > 2 && !in_array($t, $stop, true))
                ->unique()->values()->all();
        };

        $targetDetails = Cache::remember(
            "movie_details_for_similar_{$target->id}",
            now()->addDay(),
            fn () => app(MovieLookup::class)->getMovieByName($target->title)
        );

        $targetGenres = $list($targetDetails['Genre'] ?? '');
        $targetDirector = Str::lower($targetDetails['Director'] ?? '');
        $targetTokens = $tokens($target->title);

        $W_GENRE = 0.6; $W_DIRECTOR = 0.3; $W_TITLE = 0.1;

        return self::query()
            ->where('id', '!=', $target->id)
            ->get()
            ->map(function (Movie $movie) use ($targetGenres, $targetDirector, $targetTokens, $W_GENRE, $W_DIRECTOR, $W_TITLE, $list, $tokens) {
                $details = Cache::remember(
                    "movie_details_for_similar_{$movie->id}",
                    now()->addDay(),
                    fn () => app(MovieLookup::class)->getMovieByName($movie->title)
                );

                $genres = $list($details['Genre'] ?? '');
                $director = Str::lower($details['Director'] ?? '');

                $genreOverlap = count(array_intersect($targetGenres, $genres));
                $genreScore = $targetGenres ? $genreOverlap / count($targetGenres) : 0.0;

                $dirScore = ($targetDirector && $director && $targetDirector === $director) ? 1.0 : 0.0;

                $titleTokensOther = $tokens($movie->title);
                $titleOverlap = count(array_intersect($targetTokens, $titleTokensOther));
                $titleScore = $targetTokens ? $titleOverlap / count($targetTokens) : 0.0;

                $movie->similarity = ($W_GENRE * $genreScore) + ($W_DIRECTOR * $dirScore) + ($W_TITLE * $titleScore);

                $apiPoster = ($details['Poster'] ?? null);
                if (empty($movie->poster)) {
                    $movie->poster = ($apiPoster && $apiPoster !== 'N/A')
                        ? $apiPoster
                        : config('defaults.movie_picture');
                }

                return $movie;
            })
            ->filter(fn ($movie) => $movie->similarity >= 0.15)
            ->sortByDesc('similarity')
            ->take($limit)
            ->values();
    }
}
