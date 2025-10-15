<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

class BookRepository extends BaseRepository
{
    protected function model(): Book
    {
        return new Book();
    }

}
