<?php

namespace App\Services;

use App\Services\Interfaces\MovieLookup;
use Illuminate\Http\JsonResponse;
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

        if (!$response->successful() || $response->json('Response') === 'False') {
            return [];
        }

        return $response->json();
    }
}
