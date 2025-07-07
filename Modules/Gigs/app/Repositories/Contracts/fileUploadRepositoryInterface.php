<?php

namespace Modules\Gigs\Repositories\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;

interface FileUploadRepositoryInterface
{
    /**
     * Get current user's buyer ID
     *
     * @return array{buyer_id: int|null}
     */
    public function index(): array;

    /**
     * @return array<string, mixed>
     */
    public function uploadedList(Request $request): array;

    /**
     * Get list of gigs for a seller
     *
     * @param Request $request
     *
     * @return array<array{id: int, title: string}>
     */
    public function gigsList(Request $request): array;

    /**
     * Get distinct file types from orders
     *
     * @param Request $request
     *
     * @return SupportCollection<int, string>
     */
    public function orderType(Request $request): SupportCollection;

    /**
     * @return array<string, mixed>
     */
    public function orderDelete(Request $request): array;
}
