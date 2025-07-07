<?php

namespace Modules\Communication\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Communication\Repositories\Contracts\AnnouncementRepositoryInterface;
use Modules\Communication\Repositories\Contracts\ContactMessagesRepositoryInterface;
use Modules\Communication\Repositories\Eloquent\AnnouncementRepository;
use Modules\Communication\Repositories\Eloquent\ContactMessagesRepository;

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
        $this->app->bind(AnnouncementRepositoryInterface::class, AnnouncementRepository::class);
        $this->app->bind(ContactMessagesRepositoryInterface::class, ContactMessagesRepository::class);
    }
}
