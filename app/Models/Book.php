<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Book extends UploadableModel
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
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
        /* Now, if we had an external service providing lists of books like we have for movies, 
            we could create a service ajd an interface for it and not adding the whole logic here in the model as we it had been done for movies.
        */
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'picture' => $this->cover ?? config('defaults.book_picture'),
            'description' => $this->description,
            'comments' => $this->comments()->latest()->take(5)->get()->toArray(),
            'added_by' => $this->uploader->name,
        ];
    }
}
