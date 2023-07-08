<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait PaginatorTrait
{
    private function paginateItems(Collection $items, $perPage, $page)
    {
        $offset = ($page - 1) * $perPage;
        return $items->slice($offset, $perPage)->values();
    }

    protected function generatePaginator(Collection $items, $total, $perPage, $page, $path, $query)
    {
        return new LengthAwarePaginator(
            $this->paginateItems($items, $perPage, $page),
            $total,
            $perPage,
            $page,
            ['path' => $path, 'query' => $query]
        );
    }
}
