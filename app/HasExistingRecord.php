<?php

declare(strict_types = 1);

namespace App;

trait HasExistingRecord
{
    // Added as a trait so that it can be used in the books model when the API is added in
    public static function doesRecordAlreadyExists(string $title): bool
    {
        return self::where('title', $title)->exists();
    }
}
