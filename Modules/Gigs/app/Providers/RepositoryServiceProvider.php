<?php

namespace Modules\Gigs\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Gigs\Repositories\Contracts\FileUploadRepositoryInterface;
use Modules\Gigs\Repositories\Contracts\GigsInterface;
use Modules\Gigs\Repositories\Eloquent\fileUploadRepository;
use Modules\Gigs\Repositories\Eloquent\GigsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FileUploadRepositoryInterface::class, FileUploadRepository::class);
        $this->app->bind(GigsInterface::class, GigsRepository::class);
    }
}
