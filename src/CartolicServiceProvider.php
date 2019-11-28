<?php

namespace Jenky\Cartolic;

use Illuminate\Support\ServiceProvider;

class CartolicServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/cart.php' => config_path('cart.php'),
            ], 'cartolic-config');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/cart.php', 'cart'
        );

        $this->registerStorageDriver();

        $this->registerCartServices();
    }

    /**
     * Register the package storage driver.
     *
     * @return void
     */
    protected function registerStorageDriver()
    {
        $this->app->singleton(StorageManager::class, function ($app) {
            return new StorageManager($app);
        });

        $this->app->bind('cart.storage', function ($app) {
            return $app[StorageManager::class]->driver();
        });
    }

    /**
     * Register the primary cart bindings.
     *
     * @return void
     */
    protected function registerCartServices()
    {
        $this->app->singleton(Contracts\Cart::class, function ($app) {
            return new Cart($app['cart.storage']);
        });

        $this->app->alias(Contracts\Cart::class, 'cart');
    }
}
