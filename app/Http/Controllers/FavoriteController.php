<?php

namespace App\Http\Controllers;

use App\Services\FavoriteService;
use App\Http\Requests\FavoriteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;


class FavoriteController extends Controller
{
    protected $favoriteService;
    
    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }
    
    /**
     * Get all user favorites
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $favorites = $this->favoriteService->getUserFavorites($request->user()->id);
            
            return response()->json([
                'data' => $favorites,
                'count' => $favorites->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch favorites',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Add to favorites
     */
    public function store(FavoriteRequest $request): JsonResponse
    {
        try {
            $result = $this->favoriteService->addFavorite(
                $request->user()->id,
                $request->input('type'),
                $request->input('id')
            );
            
            return response()->json($result, 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add favorite',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove from favorites
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:movie,book',
            'id' => 'required|integer'
        ]);
        
        try {
            $result = $this->favoriteService->removeFavorite(
                $request->user()->id,
                $request->input('type'),
                $request->input('id')
            );
            
            return response()->json($result);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to remove favorite',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Toggle favorite status
     */
    public function toggle(FavoriteRequest $request): JsonResponse
    {
        try {
            $result = $this->favoriteService->toggleFavorite(
                $request->user()->id,
                $request->input('type'),
                $request->input('id')
            );
            
            return response()->json($result);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to toggle favorite',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Check if item is favorited
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:movie,book',
            'id' => 'required|integer'
        ]);
        
        try {
            $isFavorited = $this->favoriteService->isFavorited(
                $request->user()->id,
                $request->input('type'),
                $request->input('id')
            );
            
            return response()->json([
                'is_favorited' => $isFavorited
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to check favorite status',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}