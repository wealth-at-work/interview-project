<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Movie;
use App\Models\Book;
use Illuminate\Support\Str;

class CommentSeeder extends Seeder
{

    public function run(): void
    {
        $users = User::all();
        $movies = Movie::all();
        $books = Book::all();

        /*if ($users->isEmpty() || ($movies->isEmpty() && $books->isEmpty())) {
            $this->command->warn('⚠️ Nessun utente, film o libro trovato. Assicurati di aver eseguito prima MovieSeeder e BookSeeder.');
            return;
        }*/

        $commentsTexts = [
            'Absolutely amazing!',
            'I really loved it!',
            'A bit slow in some parts.',
            'Didn’t quite convince me.',
            'Definitely worth watching/reading!',
            'A modern masterpiece.',
            'Interesting and well-written story.',
            'Very realistic characters.',
            'Predictable ending but still enjoyable.',
            'Incredible soundtrack!',
        ];

        foreach ($users as $user) {
            $numComments = rand(3, 6);

            for ($i = 0; $i < $numComments; $i++) {
                $type = rand(0, 1) ? Movie::class : Book::class;
                $commentable = $type::inRandomOrder()->first();

                if (!$commentable) {
                    continue;
                }

                Comment::create([
                    'title' => fake()->sentence(4),
                    'body' => $commentsTexts[array_rand($commentsTexts)],
                    'user_id' => $user->id,
                    'commentable_id' => $commentable->id,
                    'commentable_type' => $type,
                ]);
            }
        }

        $this->command->info('Comments have been generated successfully.');
    }
}
