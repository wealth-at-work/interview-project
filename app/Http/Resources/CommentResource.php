<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'content' => $this->resource->content,
            'created_at' => $this->resource->created_at->diffForHumans(),
            'user' => [
                'id' => $this->resource->user->id,
                'name' => $this->resource->user->name,
            ],
        ];
    }
}
