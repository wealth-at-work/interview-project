<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieRepository extends BaseRepository
{
    protected function model(): Movie
    {
        return new Movie;
    }
}
