<?php

namespace Jenky\Cartolic\Contracts;

use Illuminate\Support\Collection;
use Jenky\Cartolic\Contracts\Fee\Collector;
use Jenky\Cartolic\Item;

interface Cart
{
    /**
     * Get all the fees.
     *
     * @return \Jenky\Cartolic\Contracts\Fee\Collector
     */
    // public function fees(): Collector;

    /**
     * Get all the cart items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function items(): Collection;

    /**
     * Get cart subtotal.
     *
     * @return mixed
     */
    public function subtotal();

    /**
     * Get cart total.
     *
     * @return mixed
     */
    public function total();

    /**
     * Determine whether the cart has a specific item.
     *
     * @param  mixed  $item
     * @return bool
     */
    public function has($item): bool;

    /**
     * Get the cart item.
     *
     * @param  mixed  $item
     * @return \Jenky\Cartolic\Item|null
     */
    public function get($item): ?Item;

    /**
     * Add an item to the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable $item
     * @param  int $quantity
     * @return bool
     */
    public function add(Purchasable $item, int $quantity = 1);

    /**
     * Update an item of the cart.
     *
     * @param  \Jenky\Cartolic\Purchasable  $item
     * @param  array  $data
     * @return \Jenky\Cartolic\Item
     */
    // public function update(Purchasable $item, array $data = []): CartItem;

    /**
     * Remove an item from the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable  $item
     * @param  int|null  $quantity
     * @return bool
     */
    public function remove(Purchasable $item, ?int $quantity = null);

    /**
     * Clear the cart.
     *
     * @return void
     */
    public function clear();

    /**
     * Save the cart to the persistent storage.
     *
     * @return mixed
     */
    public function save();
}
