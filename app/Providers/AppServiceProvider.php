<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Backend\RoleRepository;
use App\Repositories\Backend\PermissionRepository;
use App\Repositories\Backend\RoleRepositoryInterface;
use App\Repositories\Backend\PermissionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
