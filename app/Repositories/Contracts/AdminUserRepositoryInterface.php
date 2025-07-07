<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Page\Http\Requests\StoreAdminUserRequest;
use Modules\RolesPermission\Models\Role;

interface AdminUserRepositoryInterface
{
    /** @return Collection<int, Role> */
    public function getRoles(int $userId): Collection;

    public function createOrUpdateUser(StoreAdminUserRequest $request): JsonResponse;

    public function getUsersList(Request $request): JsonResponse;

    public function getUserById(int $id): JsonResponse;

    public function deleteUser(int $id): JsonResponse;

    public function getNotifications(Request $request): JsonResponse;

    public function markAllNotificationsAsRead(Request $request): JsonResponse;
    /** @return array<string, mixed> */
    public function getPaginatedNotifications(Request $request): array;

    public function markNotificationAsRead(int $id): JsonResponse;

    public function deleteNotification(int $id): JsonResponse;

    public function deleteAllNotifications(Request $request): JsonResponse;
}
