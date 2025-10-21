<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository extends BaseRepository
{
    protected function model(): Book
    {
        return new Book;
    }
}
