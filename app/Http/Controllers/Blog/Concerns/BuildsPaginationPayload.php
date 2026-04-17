<?php

namespace App\Http\Controllers\Blog\Concerns;

use Illuminate\Pagination\LengthAwarePaginator;

trait BuildsPaginationPayload
{
    /**
     * @template TItem
     *
     * @param  LengthAwarePaginator<int, TItem>  $paginator
     * @return array<string, mixed>
     */
    protected function paginationPayload(LengthAwarePaginator $paginator): array
    {
        return [
            'currentPage' => $paginator->currentPage(),
            'lastPage' => $paginator->lastPage(),
            'perPage' => $paginator->perPage(),
            'total' => $paginator->total(),
            'prevUrl' => $paginator->previousPageUrl(),
            'nextUrl' => $paginator->nextPageUrl(),
            'links' => collect(range(1, $paginator->lastPage()))
                ->map(fn (int $page): array => [
                    'page' => $page,
                    'url' => $paginator->url($page),
                    'active' => $page === $paginator->currentPage(),
                ])
                ->all(),
        ];
    }
}
