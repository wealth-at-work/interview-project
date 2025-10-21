<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\Book;

/**
 * @method \Illuminate\Database\Eloquent\Collection<int, Book> getLatest(int $limit)
 */
class BookRepository extends BaseRepository
{
    protected function model(): Book
    {
        return new Book;
    }
}
