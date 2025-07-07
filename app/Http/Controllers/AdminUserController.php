<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\AdminUserRepositoryInterface;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Page\Http\Requests\StoreAdminUserRequest;

class AdminUserController extends Controller
{
    public function __construct(
        protected AdminUserRepositoryInterface $adminUserRepository
    ) {
    }

    public function index(Request $request): View
    {
        $userId = current_user()->id ?? $request->input('user_id');
        $roles = $this->adminUserRepository->getRoles($userId);

        return view('admin.users', compact('roles'));
    }

    public function store(StoreAdminUserRequest $request): JsonResponse
    {
        return $this->adminUserRepository->createOrUpdateUser($request);
    }

    public function list(Request $request): JsonResponse
    {
        return $this->adminUserRepository->getUsersList($request);
    }

    public function edit(Request $request): JsonResponse
    {
        return $this->adminUserRepository->getUserById($request->input('id'));
    }

    public function delete(Request $request): JsonResponse
    {
        return $this->adminUserRepository->deleteUser($request->input('id'));
    }

    public function getNotifications(Request $request): JsonResponse
    {
        return $this->adminUserRepository->getNotifications($request);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        return $this->adminUserRepository->markAllNotificationsAsRead($request);
    }

    public function notifications(Request $request): ViewContract|JsonResponse
    {
        $result = $this->adminUserRepository->getPaginatedNotifications($request);

        if ($request->ajax()) {
            return response()->json($result);
        }

        $notifications = $result['notifications'] ?? collect();
        return view('admin.partials.notifications', compact('notifications'));
    }

    public function markNotificationAsRead(Request $request): JsonResponse
    {
        return $this->adminUserRepository->markNotificationAsRead($request->input('id'));
    }

    public function deleteNotification(Request $request): JsonResponse
    {
        return $this->adminUserRepository->deleteNotification($request->input('id'));
    }

    public function deleteAllNotification(Request $request): JsonResponse
    {
        return $this->adminUserRepository->deleteAllNotifications($request);
    }
}
