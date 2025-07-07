<?php

use App\Http\Middleware\Admin;
use App\Http\Middleware\CheckInstallerStatus;
use App\Http\Middleware\Customer;
use App\Http\Middleware\SecurityHeader;
use App\Http\Middleware\SetLocaleUser;
use App\Http\Middleware\MaintenanceMode;
use App\Http\Middleware\SetLocaleAdmin;
use App\Http\Middleware\UserPermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => Admin::class,
            'customer' => Customer::class,
            'setLocale' => SetLocaleAdmin::class,
            'setLocaleUser' => SetLocaleUser::class,
            'maintenance' => MaintenanceMode::class,
            'permission' => UserPermission::class,
            'checkInstallerStatus' => CheckInstallerStatus::class,
            'securityHeader' => SecurityHeader::class
        ]);
    })
    ->withExceptions(function () {
        //
    })->create();
