<?php

namespace Modules\Booking\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Booking\Repositories\Contracts\UserBookingRepositoryInterface;
use Modules\Booking\Repositories\Eloquent\UserBookingRepository;
use Modules\Gigs\Repositories\Contracts\OrderRepositoryInterface;
use Modules\Gigs\Repositories\Eloquent\OrderRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserBookingRepositoryInterface::class, UserBookingRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }
}
