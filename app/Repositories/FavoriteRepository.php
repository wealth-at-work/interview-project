<?php

namespace App\Repositories;

use App\Models\Favorite;
use Illuminate\Support\Collection;
use App\Models\User;

class FavoriteRepository
{

    /*
    Tecnhically, I could have followed the below but for emphasasing the relationship usage from User model, I have not used it but it works.
    This refers to all methods not just thisn one.


    public function getUserFavorites(int $userId): Collection
    {
        return Favorite::where('user_id', $userId)
            ->with('favoritable')
            ->get();
    }
    
    */
    
    public function getUserFavorites(int $userId): Collection
    {
        return User::findOrFail($userId)
            ->favoritedItems() 
            ->latest()
            ->get();
    }
    
    public function findByUserAndItem(int $userId, string $type, int $itemId): ?Favorite
    {
        return User::findOrFail($userId)
            ->favorites()
            ->where('favoritable_type', $type)
            ->where('favoritable_id', $itemId)
            ->first();
    }
    
    public function exists(int $userId, string $type, int $itemId): bool
    {
        return User::findOrFail($userId)
            ->favorites()
            ->where('favoritable_type', $type)
            ->where('favoritable_id', $itemId)
            ->exists();
    }
    
    public function create(int $userId, string $type, int $itemId): Favorite
    {
        $user = User::findOrFail($userId);

        return $user->favorites()->firstOrCreate([
            'favoritable_type' => $type,
            'favoritable_id' => $itemId,
        ]);
    }
    
    public function delete(int $userId, string $type, int $itemId): bool
    {
        $user = User::findOrFail($userId);

        return $user->favorites()
            ->where('favoritable_type', $type)
            ->where('favoritable_id', $itemId)
            ->delete() > 0;
    }
    
    public function deleteById(int $favoriteId): bool
    {
        return Favorite::where('id', $favoriteId)->delete() > 0;
    }
}