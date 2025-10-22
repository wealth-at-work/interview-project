<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(MovieSeeder::class);
        $this->call(BookSeeder::class);
        $this->call(CommentSeeder::class);
        //Comments to be run as last one as it depends on movies and books
    }
}
