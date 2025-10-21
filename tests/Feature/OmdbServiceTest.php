<?php

use App\Models\Movie;
use App\Services\OmdbLookup;
use Illuminate\Support\Facades\Http;

pest()->group('movie-import');

beforeEach(function () {
    $this->service = new OmdbLookup;
});

describe('getMoviesByName', function () {
    it('returns formatted movie results when search is successful', function () {
        Http::fake([
            'omdb.localhost*' => Http::response([
                'Response' => 'True',
                'Search' => [
                    [
                        'imdbID' => 'tt0241527',
                        'Title' => 'Harry Potter and the Sorcerer\'s Stone',
                        'Year' => '2001',
                        'Poster' => 'https://example.com/poster.jpg',
                    ],
                    [
                        'imdbID' => 'tt0295297',
                        'Title' => 'Harry Potter and the Chamber of Secrets',
                        'Year' => '2002',
                        'Poster' => 'https://example.com/poster2.jpg',
                    ],
                ],
            ], 200),
        ]);

        $results = $this->service->getMoviesByName('Harry Potter');

        expect($results)->toBeArray()
            ->toHaveCount(2)
            ->and($results[0])->toMatchArray([
                'id' => 'tt0241527',
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'year' => '2001',
                'picture' => 'https://example.com/poster.jpg',
                'is_already_added' => false,
            ]);

        Http::assertSent(function ($request) {
            return str_starts_with($request->url(), 'http://omdb.localhost') &&
                $request['apikey'] === 'KEY' &&
                $request['s'] === 'Harry Potter' &&
                $request['plot'] === 'full';
        });
    });

    it('marks movies as already added when they exist in database', function () {
        Movie::factory()->create(['title' => 'Harry Potter and the Sorcerer\'s Stone']);

        Http::fake([
            'http://omdb.localhost*' => Http::response([
                'Response' => 'True',
                'Search' => [
                    [
                        'imdbID' => 'tt0241527',
                        'Title' => 'Harry Potter and the Sorcerer\'s Stone',
                        'Year' => '2001',
                        'Poster' => 'https://example.com/poster.jpg',
                    ],
                ],
            ], 200),
        ]);

        $results = $this->service->getMoviesByName('Harry Potter');

        expect($results[0]['is_already_added'])->toBeTrue();
    });

    it('returns empty array when API response is unsuccessful', function () {
        Http::fake([
            'http://omdb.localhost*' => Http::response([], 500),
        ]);

        $results = $this->service->getMoviesByName('Harry Potter');

        expect($results)->toBeArray()->toBeEmpty();
    });

    it('returns empty array when API returns Response False', function () {
        Http::fake([
            'http://omdb.localhost*' => Http::response([
                'Response' => 'False',
                'Error' => 'Movie not found!',
            ], 200),
        ]);

        $results = $this->service->getMoviesByName('NonExistentMovie');

        expect($results)->toBeArray()->toBeEmpty();
    });
});

describe('getMovieByRemoteId', function () {
    it('returns formatted movie details when lookup is successful', function () {
        Http::fake([
            'http://omdb.localhost*' => Http::response([
                'Response' => 'True',
                'imdbID' => 'tt0241527',
                'Title' => 'Harry Potter and the Sorcerer\'s Stone',
                'Poster' => 'https://example.com/poster.jpg',
                'Year' => '2001',
                'Plot' => 'A young wizard\'s first year at Hogwarts...',
            ], 200),
        ]);

        $result = $this->service->getMovieByRemoteId('tt0241527');

        expect($result)->toMatchArray([
            'id' => 'tt0241527',
            'title' => 'Harry Potter and the Sorcerer\'s Stone',
            'picture' => 'https://example.com/poster.jpg',
        ]);

        Http::assertSent(function ($request) {
            return str_starts_with($request->url(), 'http://omdb.localhost') &&
                   $request['apikey'] === 'KEY' &&
                   $request['i'] === 'tt0241527' &&
                   $request['plot'] === 'full';
        });
    });

    it('returns empty array when API response is unsuccessful', function () {
        Http::fake([
            'http://omdb.localhost*' => Http::response([], 500),
        ]);

        $result = $this->service->getMovieByRemoteId('tt0241527');

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('returns empty array when API returns Response False', function () {
        Http::fake([
            'http://omdb.localhost*' => Http::response([
                'Response' => 'False',
                'Error' => 'Incorrect IMDb ID.',
            ], 200),
        ]);

        $result = $this->service->getMovieByRemoteId('invalid_id');

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('handles missing Title field gracefully', function () {
        Http::fake([
            'http://omdb.localhost*' => Http::response([
                'Response' => 'True',
                'imdbID' => 'tt0241527',
                'Poster' => 'https://example.com/poster.jpg',
                // Title is missing
            ], 200),
        ]);

        $result = $this->service->getMovieByRemoteId('tt0241527');

        expect($result)->toMatchArray([
            'id' => 'tt0241527',
            'title' => null,
            'picture' => 'https://example.com/poster.jpg',
        ]);
    });

    it('handles missing Poster field gracefully', function () {
        Http::fake([
            'http://omdb.localhost*' => Http::response([
                'Response' => 'True',
                'imdbID' => 'tt0241527',
                'Title' => 'Test Movie',
                // Poster is missing
            ], 200),
        ]);

        $result = $this->service->getMovieByRemoteId('tt0241527');

        expect($result)->toMatchArray([
            'id' => 'tt0241527',
            'title' => 'Test Movie',
            'picture' => null,
        ]);
    });

    it('uses correct API parameters for detailed movie lookup', function () {
        Http::fake([
            'http://omdb.localhost*' => Http::response([
                'Response' => 'True',
                'imdbID' => 'tt0241527',
                'Title' => 'Test Movie',
                'Poster' => 'https://example.com/poster.jpg',
            ], 200),
        ]);

        $this->service->getMovieByRemoteId('tt0241527');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'http://omdb.localhost') &&
                   $request['i'] === 'tt0241527' &&
                   $request['plot'] === 'full' &&
                   $request['apikey'] === 'KEY';
        });
    });
});
