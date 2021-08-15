<?php

namespace Jenky\Cartolic;

use Closure;

class Cartolic
{
    /**
     * The callback to calculate subtotal price of the cart items.
     *
     * @var \Closure
     */
    public static $calculateSubtotalUsing;

    /**
     * The callback to calculate total price of the cart items.
     *
     * @var \Closure
     */
    public static $calculateTotalUsing;

    /**
     * Set the callback to calculate subtotal price of the cart items.
     *
     * @param  \Closure  $callback
     * @return static
     */
    public static function calculateSubtotalUsing(Closure $callback)
    {
        static::$calculateSubtotalUsing = $callback;

        return new static();
    }

    /**
     * Set the callback to calculate total price of the cart items.
     *
     * @param  \Closure  $callback
     * @return static
     */
    public static function calculateTotalUsing(Closure $callback)
    {
        static::$calculateTotalUsing = $callback;

        return new static();
    }
}
