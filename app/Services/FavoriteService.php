<?php

// app/Services/FavoriteService.php
namespace App\Services;

use App\Repositories\FavoriteRepository;
use App\Models\Movie;
use App\Models\Book;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class FavoriteService
{
    protected $favoriteRepository;
    
    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }
    
    /**
     * Get all user favorites with formatted data
     */
    public function getUserFavorites(int $userId): Collection
    {
        $favorites = $this->favoriteRepository->getUserFavorites($userId);
        
        return $favorites->map(function ($favorite) {
            return [
                'id' => $favorite->id,
                'item' => $favorite->favoritable,
                'type' => strtolower(class_basename($favorite->favoritable_type)),
                'favoritable_id' => $favorite->favoritable_id,
                'added_at' => $favorite->created_at->toIso8601String()
            ];
        });
    }
    
    /**
     * Add item to favorites
     */
    public function addFavorite(int $userId, string $type, int $itemId): array
    {
        // Validate type
        $modelClass = $this->getModelClass($type);
        
        // Check if item exists
        if (!$modelClass::find($itemId)) {
            throw new InvalidArgumentException("The {$type} with ID {$itemId} does not exist.");
        }
        
        // Check if already favorited
        if ($this->favoriteRepository->exists($userId, $modelClass, $itemId)) {
            throw new InvalidArgumentException("This item is already in favorites.");
        }
        
        $favorite = $this->favoriteRepository->create($userId, $modelClass, $itemId);
        
        return [
            'id' => $favorite->id,
            'message' => 'Added to favorites successfully',
            'type' => $type,
            'item_id' => $itemId
        ];
    }
    
    /**
     * Remove item from favorites, needed for toggle
     */
    public function removeFavorite(int $userId, string $type, int $itemId): array
    {
        $modelClass = $this->getModelClass($type);
        
        $deleted = $this->favoriteRepository->delete($userId, $modelClass, $itemId);
        
        if (!$deleted) {
            throw new InvalidArgumentException("Favorite not found.");
        }
        
        return [
            'message' => 'Removed from favorites successfully',
            'type' => $type,
            'item_id' => $itemId
        ];
    }
    
    /**
     * Toggle favorite status
     */
    public function toggleFavorite(int $userId, string $type, int $itemId): array
    {
        $modelClass = $this->getModelClass($type);
        
        $exists = $this->favoriteRepository->exists($userId, $modelClass, $itemId);
        
        if ($exists) {
            return $this->removeFavorite($userId, $type, $itemId);
        } else {
            return $this->addFavorite($userId, $type, $itemId);
        }
    }
    
    /**
     * Check if item is favorited by user
     */
    public function isFavorited(int $userId, string $type, int $itemId): bool
    {
        $modelClass = $this->getModelClass($type);
        return $this->favoriteRepository->exists($userId, $modelClass, $itemId);
    }
    
    /**
     * Get favorite count for user, needed for some potential stats
     */
    public function getFavoriteCount(int $userId): int
    {
        return $this->favoriteRepository->getUserFavorites($userId)->count();
    }
    
    /**
     * Get model class from type string, this is needed as we have polymorphic relations
     */
    protected function getModelClass(string $type): string
    {
        $allowedTypes = [
            'movie' => Movie::class,
            'book' => Book::class,
        ];
        
        $type = strtolower($type);
        
        if (!isset($allowedTypes[$type])) {
            throw new InvalidArgumentException("Invalid type. Allowed types: " . implode(', ', array_keys($allowedTypes)));
        }
        
        return $allowedTypes[$type];
    }
}