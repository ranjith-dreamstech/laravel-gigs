<?php

namespace Modules\RolesPermission\Repositories\Contracts;

use Illuminate\Http\Request;

interface RolesPermissionRepositoryInterface
{
    /**
     * @return array<string, mixed>
     */
    public function store(Request $request): array;

    /**
     * @return array{
     *     draw: int,
     *     recordsTotal: int,
     *     recordsFiltered: int,
     *     data: \Illuminate\Support\Collection<int, \Modules\RolesPermission\Models\Role>|array<int, \Modules\RolesPermission\Models\Role>,
     *     code: int
     * }
     */
    public function list(Request $request): array;
    /**
     * @return array{status: string, code: int, message?: string, data?: \Modules\RolesPermission\Models\Role|null}
     */
    public function edit(int $id): array;
    /**
     * @return array{status: string, code: int, message: string}
     */
    public function delete(int $id): array;
    /**
     * @return array{role: \Modules\RolesPermission\Models\Role|null, modules: \Illuminate\Support\Collection<int, \Modules\RolesPermission\Models\Module>}
     */
    public function permissions(?int $roleId, ?int $userId): array;
    /**
     * @return array{code: int, message: string}
     */
    public function permissionUpdate(Request $request): array;
    /**
     * @return array{code: int, data?: mixed, message: string}
     */
    public function getUserPermissionsData(): array;
}
