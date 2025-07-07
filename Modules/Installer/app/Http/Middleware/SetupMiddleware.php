<?php

namespace Modules\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SetupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (empty(config('app.key'))) {
            Artisan::call('key:generate');
            Artisan::call('config:cache');
        }

        $setupStatus = setupStatus();
        $isSetupRoute = $request->is('setup/*');

        if ($isSetupRoute && $setupStatus) {
            return redirect()->route('home');
        }

        if (! $isSetupRoute && ! $setupStatus) {
            return redirect()->route('setup.verify');
        }

        return $next($request);
    }

}
