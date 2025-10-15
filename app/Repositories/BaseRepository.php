<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * The model instance.
     */
    abstract protected function model(): Model;

    /**
     * The column to order by for "latest".
     */
    protected function latestColumn(): string
    {
        return 'created_at';
    }

    /**
     * Apply any default filters to the query.
     */
    protected function applyDefaultFilters(Builder $query): Builder
    {
        return $query;
    }

    /**
     * Get the latest records.
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->applyDefaultFilters($this->model()->query())
            ->orderBy($this->latestColumn(), 'desc')
            ->limit($limit)
            ->get();
    }
}
