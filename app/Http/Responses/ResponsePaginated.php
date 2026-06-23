<?php

namespace App\Http\Responses;

use Illuminate\Support\Arr;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class ResponsePaginated extends PaginatedResourceResponse
{
    /** @var LengthAwarePaginator */
    protected LengthAwarePaginator $pagination;

    /**
     * Create a new resource response.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct(public $resource)
    {
        parent::__construct($resource);

        // Check if resource is paginated
        if ($resource instanceof LengthAwarePaginator) {
            $this->pagination = $resource;
        }
    }

    /**
     * Gather the meta data for the response.
     *
     * @param  array  $paginated
     * @return array
     */
    protected function meta($paginated): array
    {
        return Arr::except($paginated, [
            'data',
            'links',
            'path',
            'first_page_url',
            'last_page_url',
            'prev_page_url',
            'next_page_url',
        ]);
    }

    /**
     * Add the pagination information to the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function paginationInformation($request)
    {
        $paginated = $this->resource->resource->toArray();

        // Get the default links (first, last, prev, next)
        $links = [
            'first' => $this->trimUrl($paginated['first_page_url'] ?? null),
            'last'  => $this->trimUrl($paginated['last_page_url'] ?? null),
            'prev'  => $this->trimUrl($paginated['prev_page_url'] ?? null),
            'next'  => $this->trimUrl($paginated['next_page_url'] ?? null),
        ];

        return [
            'links' => $links,
            'meta' => $this->meta($paginated),
        ];
    }

    /**
     * Helper to strip the base URL and /api prefix
     */
    private function trimUrl(?string $url): ?string
    {
        if (!$url) return null;

        // Removes "http://localhost/api" or whatever your APP_URL is
        $base = config('app.url') . '/api';
        
        return str_replace($base, '', $url);
    }
}
