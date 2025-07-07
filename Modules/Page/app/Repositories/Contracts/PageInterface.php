<?php

namespace Modules\Page\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Page\Models\Page;

interface PageInterface
{
    /**
     * @return Collection<int, Page>
     */
    public function index(): Collection;

    /**
     * @param array<string, mixed> $data
     *
     * @return bool
     */
    public function create(array $data): bool;

    /**
     * @param int $id
     * @param array<string, mixed> $data
     *
     * @return bool
     */
    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    /**
     * @param string $slug
     *
     * @return Page|null
     */
    public function findBySlug(string $slug): ?Page;

    /**
     * @param int $id
     *
     * @return Page|null
     */
    public function findById(int $id): ?Page;

    /**
     * @param array<string, mixed> $filters
     *
     * @return LengthAwarePaginator<int, Page>
     */
    public function getPagesWithFilters(array $filters): LengthAwarePaginator;

    /**
     * @param int $parentId
     * @param int $languageId
     *
     * @return Page|null
     */
    public function getTranslatedPage(int $parentId, int $languageId): ?Page;

    /**
     * @param array<string, mixed> $request
     *
     * @return mixed
     */
    public function pageBuilderApi(array $request);
}
