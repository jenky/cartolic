<?php

namespace Jenky\Cartolic;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Jenky\Cartolic\Contracts\Cart\Cart as Contract;
use Jenky\Cartolic\Contracts\Fee\Collector;
use Jenky\Cartolic\Contracts\StorageRepository;

class CartolicApplicationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->registerCart();
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
            return $this->cart($app);
        });

        $this->app->alias(Contract::class, 'cart');
    }

    /**
     * Create new cart instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return \Jenky\Cartolic\Contracts\Cart\Cart
     */
    protected function cart(Application $app): Contract
    {
        return tap(new Cart($app->make(StorageRepository::class)), function ($cart) {
            $cart->setEventDispatcher($app->make(Dispatcher::class));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Contract::class,
        ];
    }
}
