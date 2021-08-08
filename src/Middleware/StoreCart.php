<?php

namespace Jenky\Cartolic\Middleware;

use Closure;
use Jenky\Cartolic\Contracts\Cart;

class StoreCart
{
    /**
     * The cart instance.
     *
     * @var \Jenky\Cartolic\Contracts\Cart
     */
    protected $cart;

    /**
     * Create new middleware instance.
     *
     * @param  \Jenky\Cartolic\Contracts\Cart  $cart
     * @return void
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $this->cart->save();

        return $response;
    }
}
