<?php

use App\Models\Movie;
use App\Services\Interfaces\MovieLookup;
use Inertia\Testing\AssertableInertia as Assert;

pest()->group('movie-import');

beforeEach(function () {
    // Mock the MovieLookup service
    $this->movieLookup = Mockery::mock(MovieLookup::class);
    $this->app->instance(MovieLookup::class, $this->movieLookup);
});

describe('index method', function () {
    it('renders the add movie page without search results when no search term is provided', function () {
        $response = $this->get(route('movies.add'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Movie/Add')
            ->where('searchTerm', null)
            ->where('movies', [])
        );
    });

    it('renders the add movie page with search results when search term is provided', function () {
        $searchResults = [
            [
                'id' => 'tt0241527',
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'year' => '2001',
                'picture' => 'https://example.com/poster.jpg',
                'is_already_added' => false,
            ],
        ];

        $this->movieLookup
            ->shouldReceive('getMoviesByName')
            ->with('Harry Potter')
            ->once()
            ->andReturn($searchResults);

        $response = $this->get(route('movies.add', ['q' => 'Harry Potter']));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Movie/Add')
            ->where('searchTerm', 'Harry Potter')
            ->where('movies', $searchResults)
        );
    });

    it('returns empty array when movie lookup service returns no results', function () {
        $this->movieLookup
            ->shouldReceive('getMoviesByName')
            ->with('NonExistentMovie')
            ->once()
            ->andReturn([]);

        $response = $this->get(route('movies.add', ['q' => 'NonExistentMovie']));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Movie/Add')
            ->where('searchTerm', 'NonExistentMovie')
            ->where('movies', [])
        );
    });
});

describe('store method', function () {
    it('successfully creates a movie and redirects with success message', function () {
        $movieDetails = [
            'id' => 'tt0241527',
            'title' => 'Harry Potter and the Sorcerer\'s Stone',
            'picture' => 'https://example.com/poster.jpg',
        ];

        $this->movieLookup
            ->shouldReceive('getMovieByRemoteId')
            ->with('tt0241527')
            ->once()
            ->andReturn($movieDetails);

        $response = $this->get(route('movies.store', ['id' => 'tt0241527']));

        $response->assertRedirect(route('movie_list'));
        $response->assertSessionHas('success', 'Successfully added \'Harry Potter and the Sorcerer\'s Stone\' to your library.');

        $this->assertDatabaseHas('movies', [
            'title' => 'Harry Potter and the Sorcerer\'s Stone',
            'poster' => 'https://example.com/poster.jpg',
            'added_by' => 1,
        ]);
    });

    it('redirects with error message when movie details are not found', function () {
        $this->movieLookup
            ->shouldReceive('getMovieByRemoteId')
            ->with('invalid_id')
            ->once()
            ->andReturn([]);

        $response = $this->get(route('movies.store', ['id' => 'invalid_id']));

        $response->assertRedirect(route('movies.add'));
        $response->assertSessionHas('error', 'Movie not found. Please try searching again.');

        $this->assertDatabaseCount('movies', 0);
    });

    it('handles missing poster gracefully', function () {
        $movieDetails = [
            'id' => 'tt0241527',
            'title' => 'Harry Potter and the Sorcerer\'s Stone',
            // picture is missing
        ];

        $this->movieLookup
            ->shouldReceive('getMovieByRemoteId')
            ->with('tt0241527')
            ->once()
            ->andReturn($movieDetails);

        $response = $this->get(route('movies.store', ['id' => 'tt0241527']));

        $response->assertRedirect(route('movie_list'));

        $this->assertDatabaseHas('movies', [
            'title' => 'Harry Potter and the Sorcerer\'s Stone',
            'poster' => null,
            'added_by' => 1,
        ]);
    });

    it('sets added_by to user 1 by default', function () {
        $movieDetails = [
            'id' => 'tt0241527',
            'title' => 'Test Movie',
            'picture' => 'https://example.com/poster.jpg',
        ];

        $this->movieLookup
            ->shouldReceive('getMovieByRemoteId')
            ->with('tt0241527')
            ->once()
            ->andReturn($movieDetails);

        $this->get(route('movies.store', ['id' => 'tt0241527']));

        $movie = Movie::first();
        expect($movie->added_by)->toBe(1);
    });
});
