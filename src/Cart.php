<?php

namespace Jenky\Cartolic;

use Cknow\Money\Money;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Jenky\Cartolic\Contracts\Cart as Contract;
use Jenky\Cartolic\Contracts\StorageRepository;

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
     * @return \Cknow\Money\Money
     */
    public function subtotal(): Money
    {
        return $this->items()->reduce(function ($carry, $item) {
            return $carry->add($item->total());
        }, money(0));
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
     * Determine whether the cart has a specific item.
     *
     * @param  \Jenky\Cartolic\Purchasable $purchasable
     * @return bool
     */
    public function has(Purchasable $purchasable): bool
    {
        return true;
    }

    /**
     * Find the cart item.
     *
     * @param  mixed $item
     * @return \Jenky\Cartolic\CartItem|null
     */
    public function find($item)
    {
        $id = $item instanceof CartItem ? $item->id : (string) $item;

        return $this->items()->first(function ($cartItem) use ($id) {
            return $cartItem->id === $id;
        });
    }

    /**
     * Add an item to the cart.
     *
     * @param  \Jenky\Cartolic\Purchasable $purchasable
     * @param  int $quantity
     * @return \Jenky\Cartolic\CartItem
     */
    public function add(Purchasable $purchasable, int $quantity = 1): CartItem
    {
        // Find already added items that are identical to current selection.
        $item = new CartItem($purchasable);

        if ($existing = $this->find($item)) {
            // Update existing item in cart.
        } else {
            // Otherwise, push it to the storage.
            $this->storage->push($item);
        }

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
