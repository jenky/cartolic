<?php

namespace Jenky\Cartolic;

use Illuminate\Support\ServiceProvider;
use Jenky\Cartolic\Contracts\Cart\Cart as Contract;
use Jenky\Cartolic\Contracts\Fee\Collector;
use Jenky\Cartolic\Contracts\Storage\StorageRepository;

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

        $this->registerStorageManager();

        $this->registerStorageDriver();

        $this->registerCart();
    }

    /**
     * Register the package storage driver.
     *
     * @return void
     */
    protected function registerStorageManager()
    {
        $this->app->singleton(StorageManager::class, function ($app) {
            return new StorageManager($app);
        });
    }

    /**
     * Register the package storage driver.
     *
     * @return void
     */
    protected function registerStorageDriver()
    {
        $this->app->bind(StorageRepository::class, function ($app) {
            return $app->make(StorageManager::class)->driver();
        });

        $this->app->alias(StorageRepository::class, 'cart.storage');
    }

    /**
     * Register the primary cart bindings.
     *
     * @return void
     */
    protected function registerCart()
    {
        $this->app->singleton(Collector::class, Fees::class);

        $this->app->singleton(Contract::class, function ($app) {
            return new Cart($app);
        });

        $this->app->alias(Contract::class, 'cart');
    }
}
