<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Movie::firstOrCreate([
            'title' => 'Harry Potter and the Prisoner of Azkaban',
        ],
        [
            'title' => 'Harry Potter and the Prisoner of Azkaban',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BMTY4NTIwODg0N15BMl5BanBnXkFtZTcwOTc0MjEzMw@@._V1_.jpg',
            'added_by' => User::inRandomOrder()->first()->id,
        ]);
        Movie::firstOrCreate([
            'title' => 'Harry Potter and the Chamber of Secrets',
        ],
        [
            'title' => 'Harry Potter and the Chamber of Secrets',
            'poster' => 'https://static.wikia.nocookie.net/listofdeaths/images/6/6b/Chamba.jpg',
            'added_by' => User::inRandomOrder()->first()->id,
        ]);
        Movie::firstOrCreate([
            'title' => 'Harry Potter and the Deathly Hallows',
        ],[
            'title' => 'Harry Potter and the Deathly Hallows',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BMTQ2OTE1Mjk0N15BMl5BanBnXkFtZTcwODE3MDAwNA@@._V1_.jpg',
            'added_by' => User::inRandomOrder()->first()->id,
        ]);
        Movie::firstOrCreate([
            'title' => 'Harry Potter and the Deathly Hallows Part 2',
        ],
        [
            'title' => 'Harry Potter and the Deathly Hallows Part 2',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BOTA1Mzc2N2ItZWRiNS00MjQzLTlmZDQtMjU0NmY1YWRkMGQ4XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
            'added_by' => User::inRandomOrder()->first()->id,
        ]);

        Movie::firstOrCreate([
            'title' => 'Interstellar',
        ],
        [
            'title' => 'Interstellar',
            'poster' => 'https://static.wikia.nocookie.net/listofdeaths/images/0/01/Interstellar.jpg',
            'added_by' => User::inRandomOrder()->first()->id,
        ]);
        Movie::firstOrCreate([
            'title' => 'Pulp Fiction',
        ],
        [
            'title' => 'Pulp Fiction',
            'poster' => 'https://static.wikia.nocookie.net/listofdeaths/images/4/4b/Pulp_Fiction_poster.png',
            'added_by' => User::inRandomOrder()->first()->id,
        ]);
    }
}
