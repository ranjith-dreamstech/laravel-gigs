<?php

use Illuminate\Support\Facades\Route;
use Modules\RolesPermission\Http\Controllers\RolesPermissionController;

Route::group(['middleware' => ['checkInstallerStatus', 'setLocale']], function () {
    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
        Route::get('/roles-permissions', [RolesPermissionController::class, 'index'])->name('admin.roles-permisions')->middleware('permission');
        Route::group(['prefix' => 'role'], function () {
            Route::post('/list', [RolesPermissionController::class, 'list'])->name('admin.role.index');
            Route::post('/save', [RolesPermissionController::class, 'store'])->name('admin.role.store');
            Route::post('/delete', [RolesPermissionController::class, 'delete'])->name('admin.role.delete');
            Route::get('/edit/{id}', [RolesPermissionController::class, 'edit'])->name('admin.role.edit');
        });

        Route::get('/permissions/{encrypted_role_id}', [RolesPermissionController::class, 'permissions'])->name('admin.permissions');
        Route::group(['prefix' => 'permission'], function () {
            Route::post('/update', [RolesPermissionController::class, 'permissionUpdate'])->name('admin.permission.update');
        });
        Route::get('/get-user-permissions', [RolesPermissionController::class, 'getUserPermissionsData'])->name('admin.user-permissions');
    });
});
