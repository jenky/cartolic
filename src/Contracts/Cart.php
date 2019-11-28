<?php

namespace Jenky\Cartolic\Contracts;

use Illuminate\Support\Collection;

interface Cart
{
    /**
     * Get all the cart items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function items(): Collection;

    /**
     * Get cart sub total.
     *
     * @param  bool $withoutDiscounts
     * @return \Cknow\Money\Money
     */
    // public function subTotal(bool $withoutDiscounts = true): Money;

    /**
     * Get cart total.
     *
     * @param  bool $withoutDiscounts
     * @return \Cknow\Money\Money
     */
    // public function total(bool $withoutDiscounts = true): Money;

    /**
     * Find the cart items.
     *
     * @param  mixed $id
     * @return \App\Cart\Storage\CartItem|null
     */
    // public function find($id);

    /**
     * Add an item to the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchaseable $item
     * @param  int $quantity
     * @param  array $options
     * @param  array $subItems
     * @param  \Cknow\Money\Money|null $price
     * @return \App\Cart\Storage\CartItem
     */
    // public function add(Purchaseable $item, int $quantity = 1, array $options = [], array $subItems = [], Money $price = null): CartItem;

    /**
     * Update an item of the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchaseable $item
     * @param  array $data
     * @return \App\Cart\Storage\CartItem
     */
    // public function update(Purchaseable $item, array $data = []): CartItem;

    /**
     * Remove an item from the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchaseable $item
     * @return void
     */
    // public function remove(Purchaseable $item);

    /**
     * Clear cart.
     *
     * @return void
     */
    // public function clear();
}
