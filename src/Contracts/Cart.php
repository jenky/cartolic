<?php

namespace Jenky\Cartolic\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use JsonSerializable;

interface Cart extends Arrayable, Jsonable, JsonSerializable
{
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
     * Find the cart item.
     *
     * @param  mixed $item
     * @return \Jenky\Cartolic\CartItem|null
     */
    public function find($item);

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
     * @param  \Jenky\Cartolic\Purchasable $item
     * @return void
     */
    public function remove(Purchasable $item);

    /**
     * Clear the cart.
     *
     * @return void
     */
    public function clear();
}
