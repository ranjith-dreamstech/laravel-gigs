<?php

namespace Modules\Category\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Category\Repositories\Contracts\CategoryInterface;
use Modules\Category\Repositories\Eloquent\CategoryRepository;

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
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
    }
}
