<?php

namespace Modules\MenuManagement\Repositories\Contracts;

use Illuminate\Support\Collection;

interface MenuManagementInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): mixed;

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function find(int $id): ?object;

    /**
     * @param array<string, mixed> $filters
     *
     * @return array<int, mixed>
     */
    public function all(array $filters = []): array;

    /**
     * @param array<string, mixed> $conditions
     */
    public function exists(array $conditions): bool;

    /**
     * @return Collection<int, stdClass>
     */
    public function getPagesByLanguage(int $languageId): Collection;

    /**
     * @return Collection<int, Menu>
     */
    public function getMenusByLanguage(int $languageId): Collection;

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function updateMenuItems(int $menuId, array $items): bool;
}
