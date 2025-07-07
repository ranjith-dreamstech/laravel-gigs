<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\RolesPermission\Models\Permission;

class PermissionService
{
    private const CACHE_TTL = 86400; // 24 hours
    private const ADMIN_USER_TYPE = 1;

    /**
     * Get user permissions with caching
     *
     * @param int|string|null $userId
     * @return Collection<int, Permission>
     */
    public function getUserPermissions(int|string|null $userId = null): Collection
    {
        $user = $userId ? User::find($userId) : $this->getCurrentUser();

        if (!$user || empty($user->role_id)) {
            return collect();
        }

        $cacheKey = "permissions_{$user->role_id}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            return Permission::where('permissions.role_id', $user->role_id)
                ->whereHas('role', function ($query) {
                    $query->where('status', 1);
                })
                ->select(
                    'permissions.module_id',
                    'permissions.create',
                    'permissions.edit',
                    'permissions.view',
                    'permissions.delete',
                    'permissions.allow_all'
                )
                ->with(['module:id,module_slug'])
                ->get();
        });
    }

    /**
     * Check if user has permission for specific module and action
     *
     * @param string|array $moduleSlug
     * @param string $action
     * @param int|null $userId
     * @return bool
     */
    public function hasPermission(string|array $moduleSlug, string $action, ?int $userId = null): bool
    {
        $user = $userId ? User::find($userId) : $this->getCurrentUser();
        
        if (!$user) {
            return false;
        }

        // Admin users have all permissions
        if ($this->isAdmin($user)) {
            return true;
        }

        $permissions = $this->getUserPermissions($userId);
        return $this->checkModulePermission($permissions, $moduleSlug, $action);
    }

    /**
     * Check permission for multiple modules
     *
     * @param Collection $permissions
     * @param string|array $moduleSlug
     * @param string $action
     * @return bool
     */
    private function checkModulePermission(Collection $permissions, string|array $moduleSlug, string $action): bool
    {
        $moduleSlugs = is_array($moduleSlug) ? $moduleSlug : [$moduleSlug];

        foreach ($moduleSlugs as $slug) {
            $permission = $permissions->firstWhere(function ($perm) use ($slug) {
                return $perm->module && $perm->module->module_slug === $slug;
            });

            if ($permission && $this->hasActionPermission($permission, $action)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if permission has specific action
     *
     * @param Permission $permission
     * @param string $action
     * @return bool
     */
    private function hasActionPermission(Permission $permission, string $action): bool
    {
        // Check if allow_all is enabled
        if (isset($permission->allow_all) && $permission->allow_all === 1) {
            return true;
        }

        // Check specific action permission
        return isset($permission->$action) && $permission->$action === 1;
    }

    /**
     * Check if user is admin
     *
     * @param User $user
     * @return bool
     */
    public function isAdmin(User $user): bool
    {
        return ($user->user_type ?? '') === self::ADMIN_USER_TYPE;
    }

    /**
     * Get current authenticated user
     *
     * @param string|null $guard
     * @return Authenticatable|null
     */
    public function getCurrentUser(?string $guard = null): ?Authenticatable
    {
        $guard = $guard ?? Auth::getDefaultDriver();

        return Auth::guard($guard)->check()
            ? Auth::guard($guard)->user()
            : null;
    }

    /**
     * Clear user permissions cache
     *
     * @param int|null $roleId
     * @return bool
     */
    public function clearPermissionsCache(?int $roleId = null): bool
    {
        if ($roleId) {
            return Cache::forget("permissions_{$roleId}");
        }

        // Clear all permission caches (you might want to use tags for better performance)
        $keys = Cache::getRedis()->keys('*permissions_*');
        foreach ($keys as $key) {
            Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
        }

        return true;
    }

    /**
     * Get all permissions for a role
     *
     * @param int $roleId
     * @return Collection
     */
    public function getRolePermissions(int $roleId): Collection
    {
        return Permission::where('role_id', $roleId)
            ->with(['module:id,module_slug,name'])
            ->get();
    }

    /**
     * Update permissions for a role
     *
     * @param int $roleId
     * @param array $permissions
     * @return bool
     */
    public function updateRolePermissions(int $roleId, array $permissions): bool
    {
        try {
            foreach ($permissions as $moduleId => $actions) {
                Permission::updateOrCreate(
                    ['role_id' => $roleId, 'module_id' => $moduleId],
                    [
                        'create' => $actions['create'] ?? 0,
                        'edit' => $actions['edit'] ?? 0,
                        'view' => $actions['view'] ?? 0,
                        'delete' => $actions['delete'] ?? 0,
                        'allow_all' => $actions['allow_all'] ?? 0,
                    ]
                );
            }

            // Clear cache after updating permissions
            $this->clearPermissionsCache($roleId);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if user can access specific menu
     *
     * @param string $menu
     * @param int|null $userId
     * @return bool
     */
    public function canAccessMenu(string $menu, ?int $userId = null): bool
    {
        $user = $userId ? User::find($userId) : $this->getCurrentUser();
        
        if (!$user) {
            return false;
        }

        if ($this->isAdmin($user)) {
            return true;
        }

        // You can add specific menu access logic here
        // For now, using the existing isAccessMenu logic
        if ($menu === 'reservation') {
            $value = \Modules\GeneralSetting\Models\GeneralSetting::where([
                'group_id' => 20, 
                'key' => 'reservation'
            ])->value('value') ?? 0;
            
            return (bool) $value;
        }

        return false;
    }

    /**
     * Get user's role name
     *
     * @param int|null $userId
     * @return string|null
     */
    public function getUserRoleName(?int $userId = null): ?string
    {
        $user = $userId ? User::find($userId) : $this->getCurrentUser();
        
        if (!$user || !$user->role_id) {
            return null;
        }

        return Cache::remember("role_name_{$user->role_id}", self::CACHE_TTL, function () use ($user) {
            $role = \Modules\RolesPermission\Models\Role::find($user->role_id);
            return $role ? $role->name : null;
        });
    }
}