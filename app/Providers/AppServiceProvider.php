<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Purchase\CartItemService;
use App\Services\Purchase\CustomerService;
use App\Services\Purchase\MemberPassService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CustomerService::class, function ($app) {
            return new CustomerService();
        });
    
        $this->app->singleton(CartItemService::class, function ($app) {
            return new CartItemService();
        });
    
        $this->app->singleton(MemberPassService::class, function ($app) {
            return new MemberPassService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
