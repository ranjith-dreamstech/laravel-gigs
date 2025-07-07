<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface BlogRepositoryInterface
{
    /**
     * Fetch list of blogs.
     *
     * @param Request $request
     *
     * @return array
     */
    public function fetchBlogs(Request $request): array;

    /**
     * Fetch a specific blog by ID.
     *
     * @param int $id
     *
     * @return array
     */
    public function fetchBlog(string $slug): array;

    /**
     * Store a blog review.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function storeReview(Request $request): JsonResponse;
}
