<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Backend\RoleRepository;
use App\Repositories\Backend\UserRepository;
use App\Repositories\Backend\OrderRepository;
use App\Repositories\Backend\PermissionRepository;
use App\Repositories\Backend\RoleRepositoryInterface;
use App\Repositories\Backend\UserRepositoryInterface;
use App\Repositories\Backend\OrderRepositoryInterface;
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(\App\Repositories\Backend\ProductRepository::class, \App\Repositories\Backend\ProductRepository::class); //Backend
        $this->app->bind(\App\Repositories\API\ProductRepository::class, \App\Repositories\API\ProductRepository::class); //API
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
