<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;

class BookSeeder extends Seeder
{
    public function run(): void
    {

        $user = User::inRandomOrder()->first();

        $books = [
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'cover' => 'https://m.media-amazon.com/images/I/71kxa1-0mfL.jpg',
                'added_by' => $user->id,
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'cover' => 'https://m.media-amazon.com/images/I/81OdwZ9IUIL.jpg',
                'added_by' => $user->id,
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'cover' => 'https://m.media-amazon.com/images/I/81af+MCATTL.jpg',
                'added_by' => $user->id,
            ],
            [
                'title' => 'Moby Dick',
                'author' => 'Herman Melville',
                'cover' => 'https://m.media-amazon.com/images/I/71xBLRBYOiL.jpg',
                'added_by' => $user->id,
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'cover' => 'https://m.media-amazon.com/images/I/91HHqVTAJQL.jpg',
                'added_by' => $user->id,
            ],
        ];

        foreach ($books as $data) {
            Book::create($data);
        }
    }
}
