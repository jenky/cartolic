<?php

namespace Jenky\Cartolic\Contracts;

interface Item extends Purchasable
{
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
}
