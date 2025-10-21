<?php

namespace App\Services\Interfaces;

interface MovieLookup
{
    public function getMovieByName(string $name): array;

    public function getMoviesByName(string $name): array;

    public function getMovieByRemoteId(string $remoteId): array;
}
