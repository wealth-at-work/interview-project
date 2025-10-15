<?php

namespace App\Services\Interfaces;

use Illuminate\Http\JsonResponse;

interface MovieLookup
{
    public function getMovieByName(string $name): array;
}
