<?php

namespace Modules\Finance\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Finance\Repositories\Contracts\FinanceRepositoryInterface;
use Modules\Finance\Repositories\Eloquent\FinanceRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    public function registerBindings(): void
    {
        $this->app->bind(FinanceRepositoryInterface::class, FinanceRepository::class);
    }
}
