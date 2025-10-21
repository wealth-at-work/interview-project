<?php

namespace App\Repositories;

use App\Models\Movie;

/**
 * @method \Illuminate\Database\Eloquent\Collection<int, Movie> getLatest(int $limit)
 */
class MovieRepository extends BaseRepository
{
    protected function model(): Movie
    {
        return new Movie;
    }
}
