<?php

namespace Jenky\Cartolic;

use Brick\Money\MoneyBag;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Jenky\Cartolic\Contracts\Cart as Contract;
use Jenky\Cartolic\Contracts\Item;
use Jenky\Cartolic\Contracts\Money;
use Jenky\Cartolic\Contracts\Purchasable;
use Jenky\Cartolic\Contracts\Storage\StorageRepository;

class Cart implements Contract
{
    use Macroable;

    /**
     * The storage driver instance.
     *
     * @var \Jenky\Cartolic\Contracts\StorageRepository
     */
    protected $storage;

    /**
     * Create a new cart instance.
     *
     * @param  \Jenky\Cartolic\Contracts\StorageRepository $storage
     * @return void
     */
    public function __construct(StorageRepository $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Get all the cart items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function items(): Collection
    {
        return $this->storage->get();
    }

    /**
     * Get cart subtotal.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function subtotal(): Money
    {
        return $this->items()->reduce(function ($carry, Item $item) {
            return $carry->add($item->total()->toMoney());
        }, new MoneyBag);
    }

    /**
     * Get cart total.
     *
     * @return \Cknow\Money\Money
     */
    public function total(): Money
    {
        return $this->subtotal();
    }

    /**
     * Add an item to the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable $item
     * @param  int $quantity
     * @return \Jenky\Cartolic\Contracts\Item
     */
    public function add(Purchasable $purchasable, int $quantity = 1): Item
    {
        $item = new CartItem($purchasable);

        // Find already added items that are identical to current selection.

        // Otherwise, push it to the storage.
        $this->storage->push($item);

        return $item;
    }

    /**
     * Clear the cart.
     *
     * @return void
     */
    public function clear()
    {
        //
    }
}
