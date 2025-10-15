<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends UploadableModel
{
    public function formatForIndex(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'picture' => $this->cover ?? config('defaults.book_picture'),
            'added_by' => $this->uploader->name
        ];
    }

    public function formatForShow(): array
    {
        //to implement still!
        return [

        ];
    }
}
