<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

class Book extends UploadableModel
{
    /**
     * @return MorphMany<Comment, $this>
     */
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
            'added_by' => $this->uploader->name,
        ];
    }

    public function formatForShow(): array
    {
        // to implement still!
        return [

        ];
    }
}
