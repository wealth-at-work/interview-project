<?php

namespace App\Models;

use App\Services\Interfaces\MovieLookup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

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

                //What is wrong with this implementation?
                //1 External api call inside model method
                //2 Cache key is tied to db but the data is queried by title (This leads to assumes that titles are unique)   
                //3 MovieDeatils can be null
                //4 Difficult to test
                return [
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
                                ? $movieDetails['Metascore'] . '/100'
                                : null,
                        ],
                        'rotten_tomatoes' => [
                            'score' => collect($movieDetails['Ratings'] ?? [])->firstWhere('Source', 'Rotten Tomatoes')['Value'] ?? null,
                        ],
                    ],
                    'poster' => $this->cover ?? $movieDetails['Poster'] ?? config('defaults.movie_picture'),
                    'director' => $movieDetails['Director'] !== 'N/A' ? $movieDetails['Director'] : null,
                    'writer' => $movieDetails['Writer'] !== 'N/A' ? $movieDetails['Writer'] : null,
                    'actors' => isset($movieDetails['Actors']) && $movieDetails['Actors'] !== 'N/A'
                        ? explode(', ', $movieDetails['Actors'])
                        : [],
                ];
            }
        );
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
}
