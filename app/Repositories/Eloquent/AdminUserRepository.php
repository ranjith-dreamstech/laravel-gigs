<?php

namespace App\Repositories\Eloquent;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserDetail;
use App\Repositories\Contracts\AdminUserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Page\Http\Requests\StoreAdminUserRequest;
use Modules\RolesPermission\Models\Role;

class AdminUserRepository implements AdminUserRepositoryInterface
{
    /**
     * @return Collection<int, Role>
     */
    public function getRoles(int $userId): Collection
    {
        return Role::select('id', 'role_name')
            ->where('status', 1)
            ->where('created_by', $userId)
            ->get();
    }

    /**
     * @return JsonResponse
     */
    public function createOrUpdateUser(StoreAdminUserRequest $request): JsonResponse
    {
        $id = $request->id ?? '';

        $successMsg = empty($id) ? __('admin.user_management.user_create_success') : __('admin.user_management.user_update_success');
        $errorMsg = empty($id) ? __('admin.common.default_create_error') : __('admin.common.default_update_error');

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'role_id' => $request->role_id,
                'user_type' => 2,
                'status' => $request->status ?? 1,
            ];
            $userDetailsData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'parent_id' => current_user()->id ?? $request->user_id,
            ];

            if (empty($id)) {
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    if ($file instanceof UploadedFile) {
                        $userDetailsData['profile_image'] = uploadFile($file, 'profile');
                    }
                }
                $userData['password'] = Hash::make($request->password);
                $user = User::create($userData);

                $userDetailsData['user_id'] = $user->id;
                UserDetail::create($userDetailsData);
            } else {
                $user = UserDetail::where('user_id', $id)->first();
                $oldImage = '';
                if ($user) {
                    $oldImage = $user->profile_image;
                }

                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    if ($file instanceof UploadedFile) {
                        $userDetailsData['profile_image'] = uploadFile($file, 'profile', $oldImage);
                    }
                }

                User::where('id', $id)->update($userData);
                UserDetail::updateOrCreate(
                    ['user_id' => $id],
                    $userDetailsData
                );
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => $successMsg,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $errorMsg,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getUsersList(Request $request): JsonResponse
    {
        try {
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $columnIndex = $request->order[0]['column'] ?? 0;
            $columnName = $request->columns[$columnIndex]['data'] ?? 'full_name';
            $orderDir = $request->order[0]['dir'] ?? 'asc';

            $userId = current_user()->id ?? $request->user_id;

            $query = User::select(
                'users.id',
                DB::raw("CONCAT(user_details.first_name, ' ', user_details.last_name) as full_name"),
                'users.email',
                'users.phone_number',
                'users.status',
                'user_details.profile_image',
                'users.role_id',
                'roles.role_name'
            )
                ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->where(['user_details.parent_id' => $userId]);

            if ($request->has('search') && ! empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->orWhere('users.email', 'LIKE', "%{$search}%")
                        ->orWhere('users.phone_number', 'LIKE', "%{$search}%")
                        ->orWhere('user_details.first_name', 'LIKE', "%{$search}%")
                        ->orWhere('user_details.last_name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('role_ids') && ! empty($request->role_ids)) {
                $query->whereIn('users.role_id', $request->role_ids);
            }

            if (
                $request->has('sort_by_status')
                && ! empty($request->sort_by_status) || $request->sort_by_status === '0'
            ) {
                $status = $request->sort_by_status;
                $query->where('users.status', $status);
            }

            if ($request->has('sort_by') && ! empty($request->sort_by)) {
                switch (strtolower($request->sort_by)) {
                    case 'latest':
                        $query->orderBy('users.created_at', 'desc');
                        break;
                    case 'ascending':
                        $query->orderByRaw("LOWER(CONCAT_WS(' ', user_details.first_name, user_details.last_name)) asc");
                        break;
                    case 'descending':
                        $query->orderByRaw("LOWER(CONCAT_WS(' ', user_details.first_name, user_details.last_name)) desc");
                        break;
                    case 'last month':
                        $startDate = \Carbon\Carbon::now()->subMonth()->startOfMonth();
                        $endDate = \Carbon\Carbon::now()->subMonth()->endOfMonth();
                        $query->whereBetween('users.created_at', [$startDate, $endDate]);
                        break;
                    case 'last 7 days':
                        $startDate = \Carbon\Carbon::now()->subDays(7)->startOfDay();
                        $endDate = \Carbon\Carbon::now()->endOfDay();
                        $query->whereBetween('users.created_at', [$startDate, $endDate]);
                        break;
                    default:
                        break;
                }
            }

            if ($columnName === 'full_name') {
                $query->orderByRaw("LOWER(CONCAT_WS(' ', user_details.first_name, user_details.last_name)) {$orderDir}");
            } else {
                $query->orderBy($columnName, $orderDir);
            }

            $totalRecords = User::leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
                ->where(['user_details.parent_id' => $userId])
                ->count();
            $filteredRecords = $query->count();

            $query->offset($start)->limit($length);
            $users = $query->get();

            $users->map(function ($user) {
                $profileImage = is_string($user->profile_image) ? $user->profile_image : '';
                $user->profile_image = uploadedAsset($profileImage, 'profile');
                $user->full_name = $user->full_name ? ucwords($user->full_name) : '';

                return $user;
            });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $users,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.common.default_retrieve_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getUserById(int $id): JsonResponse
    {
        $data = User::select(
            'users.id',
            'users.email',
            'users.phone_number',
            'users.role_id',
            'users.status',
            'user_details.first_name',
            'user_details.last_name',
            'user_details.profile_image',
        )
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->where(['users.user_type' => 2, 'users.id' => $id])
            ->first();

        if ($data) {
            $profileImage = is_string($data->profile_image) ? $data->profile_image : '';
            $data->profile_image = uploadedAsset($profileImage, 'profile');
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $data,
        ], 200);
    }

    /**
     * @return JsonResponse
     */
    public function deleteUser(int $id): JsonResponse
    {
        try {
            User::where('id', $id)->delete();
            UserDetail::where('user_id', $id)->delete();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.user_management.user_delete_success'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.common.default_delete_error'),
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getNotifications(Request $request): JsonResponse
    {
        $authUser = Auth::guard('admin')->user();
        if ($authUser !== null) {
            $notifications = Notification::where('user_id', $authUser->id)->where('is_read', 0)->orderBy('created_at', 'desc')->limit(10)->get();
            $notificationCount = Notification::where('user_id', $authUser->id)->where('is_read', 0)->count();
        } else {
            $notifications = collect();
            $notificationCount = 0;
        }
        /** @var view-string $viewName */
        $viewName = 'admin.partials.notification-popup';

        $html = view($viewName, compact('notifications'))->render();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'html' => $html,
            'auth' => $authUser,
            'count' => $notificationCount,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function markAllNotificationsAsRead(Request $request): JsonResponse
    {
        $authUser = Auth::guard('admin')->user();
        if ($authUser !== null && Notification::where('user_id', $authUser->id)->where('is_read', 0)->count() > 0) {
            Notification::where('user_id', $authUser->id)->update(['is_read' => 1]);
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('web.user.all_notifocations_marked_as_read'),
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'code' => 500,
            'message' => __('web.user.no_new_notifications_found'),
        ], 200);
    }

    /**
     * @return array <string, mixed>
     */
    public function getPaginatedNotifications(Request $request): array
    {
        $authUser = Auth::guard('admin')->user();
        $notifications = collect();
        if ($authUser !== null) {
            $notifications = Notification::where('user_id', $authUser->id)->where('is_read', 0)->orderBy('created_at', 'desc')->paginate(10);
            if ($request->ajax()) {
                /** @var view-string $viewName */
                $viewName = 'admin.partials.notification-items';
                $html = view($viewName, compact('notifications'))->render();
                return [
                    'status' => 'success',
                    'html' => $html,
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'prev_page_url' => $notifications->previousPageUrl(),
                    'next_page_url' => $notifications->nextPageUrl(),
                    'count' => $notifications->total(),
                ];
            }
        }
        return ['notifications' => $notifications];
    }

    /**
     * @return JsonResponse
     */
    public function markNotificationAsRead(int $id): JsonResponse
    {
        Notification::where('id', $id)->update(['is_read' => 1]);
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => __('web.user.notification_marked_as_read'),
        ], 200);
    }

    /**
     * @return JsonResponse
     */
    public function deleteNotification(int $id): JsonResponse
    {
        Notification::where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => __('web.user.notification_deleted'),
        ], 200);
    }

    /**
     * @return JsonResponse
     */
    public function deleteAllNotifications(Request $request): JsonResponse
    {
        $authUser = Auth::guard('admin')->user();
        if ($authUser !== null) {
            Notification::where('user_id', $authUser->id)->delete();
        }
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => __('web.user.all_notifocations_marked_as_read'),
        ], 200);
    }
}
