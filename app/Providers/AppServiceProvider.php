<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\API\PaymentRepository;
use App\Repositories\Backend\RoleRepository;
use App\Repositories\Backend\UserRepository;
use App\Repositories\Backend\OrderRepository;
use App\Repositories\Backend\ProductRepository;
use App\Repositories\Backend\PermissionRepository;
use App\Repositories\API\PaymentRepositoryInterface;
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
        $this->app->bind(ProductRepository::class, ProductRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(\App\Repositories\API\ProductRepository::class, \App\Repositories\API\ProductRepository::class);
        $this->app->bind(\App\Repositories\API\OrderRepositoryInterface::class, \App\Repositories\API\OrderRepository::class);
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
