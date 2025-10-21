<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class UploadableModel extends Model
{
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
