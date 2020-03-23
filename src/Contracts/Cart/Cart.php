<?php

namespace Jenky\Cartolic\Contracts\Cart;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Jenky\Cartolic\Contracts\Fee\Collector;
use Jenky\Cartolic\Contracts\Money;
use Jenky\Cartolic\Contracts\Purchasable;
use JsonSerializable;

interface Cart extends Arrayable, Jsonable, JsonSerializable
{
    /**
     * Get all the fees.
     *
     * @return \Jenky\Cartolic\Contracts\Fee\Collector
     */
    public function fees(): Collector;

    /**
     * Get all the cart items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function items(): Collection;

    /**
     * Get cart subtotal.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function subtotal(): Money;

    /**
     * Get cart total.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function total(): Money;

    /**
     * Determine whether the cart has a specific item.
     *
     * @param  mixed $item
     * @return bool
     */
    public function has($item): bool;

    /**
     * Get the cart item.
     *
     * @param  mixed $item
     * @return \Jenky\Cartolic\Contracts\Cart\Item|null
     */
    public function get($item): ?Item;

    /**
     * Add an item to the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable $item
     * @param  int $quantity
     * @return \Jenky\Cartolic\Contracts\Item
     */
    public function add(Purchasable $item, int $quantity = 1): Item;

    /**
     * Update an item of the cart.
     *
     * @param  \Jenky\Cartolic\Purchasable $item
     * @param  array $data
     * @return \Jenky\Cartolic\CartItem
     */
    // public function update(Purchasable $item, array $data = []): CartItem;

    /**
     * Remove an item from the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable $item
     * @param  int|null $quantity
     * @return \Jenky\Cartolic\Contracts\Item|null
     */
    public function remove(Purchasable $item, ?int $quantity = null): ?Item;

    /**
     * Clear the cart.
     *
     * @return void
     */
    public function clear();
}
