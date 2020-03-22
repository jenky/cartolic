<?php

namespace Jenky\Cartolic\Contracts\Cart;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Jenky\Cartolic\Contracts\Money;
use Jenky\Cartolic\Contracts\Purchasable;
use JsonSerializable;

interface Item extends Arrayable, Jsonable, JsonSerializable
{
    /**
     * Get the purchasable item hash.
     *
     * @return string|int
     */
    // public function id();

    /**
     * Get the item quantity.
     *
     * @return int
     */
    public function quantity(): int;

    /**
     * Increment item quantity by a given amount.
     *
     * @param  int $amount
     * @return mixed
     */
    public function increment(int $amount = 1);

    /**
     * Decrement item quantity by a given amount.
     *
     * @param  int $amount
     * @return mixed
     */
    public function decrement(int $amount = 1);

    /**
     * Get the item subtotal.
     *
     * @return int
     */
    public function subtotal(): Money;

    /**
     * Get the item total.
     *
     * @return int
     */
    public function total(): Money;

    /**
     * Get the purchasable item.
     *
     * @return \Jenky\Cartolic\Contracts\Purchasable
     */
    public function purchasable(): Purchasable;
}
