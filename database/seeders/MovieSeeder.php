<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*if (User::count() === 0) {
            User::factory(3)->create();
        }*/

        $user = User::inRandomOrder()->first();

        $movies = [
            [
                'title' => 'The Shawshank Redemption',
                'poster' => 'https://m.media-amazon.com/images/I/51NiGlapXlL._AC_.jpg',
                'added_by' => $user->id,
            ],
            [
                'title' => 'The Godfather',
                'poster' => 'https://m.media-amazon.com/images/I/71xZpR3jS3L._AC_SL1500_.jpg',
                'added_by' => $user->id,
            ],
            [
                'title' => 'The Dark Knight',
                'poster' => 'https://m.media-amazon.com/images/I/51k0qa6qSSL._AC_.jpg',
                'added_by' => $user->id,
            ],
            [
                'title' => 'Pulp Fiction',
                'poster' => 'https://m.media-amazon.com/images/I/81c2+q9qQAL._AC_SL1500_.jpg',
                'added_by' => $user->id,
            ],
            [
                'title' => 'Inception',
                'poster' => 'https://m.media-amazon.com/images/I/81p+xe8cbnL._AC_SL1500_.jpg',
                'added_by' => $user->id,
            ],
        ];

        foreach ($movies as $data) {
            Movie::create($data);
        }
    }
}
