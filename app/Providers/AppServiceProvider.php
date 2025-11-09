<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CommunityRepositoryInterface;
use App\Repositories\CommunityRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CommunityRepositoryInterface::class, CommunityRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
