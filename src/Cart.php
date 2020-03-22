<?php

namespace Jenky\Cartolic;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Jenky\Cartolic\Contracts\Cart\Cart as Contract;
use Jenky\Cartolic\Contracts\Cart\Item;
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
        return $this->storage->get()->values();
    }

    /**
     * Get cart subtotal.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function subtotal(): Money
    {
        return $this->items()->reduce(function ($carry, Item $item) {
            return $carry->plus($item->total()->toMoney());
        }, new \Jenky\Cartolic\Money(\Brick\Money\Money::zero('USD')));
    }

    /**
     * Get cart total.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function total(): Money
    {
        return $this->subtotal();
    }

    /**
     * Resolve the item id.
     *
     * @param  mixed $item
     * @return string|int
     */
    protected function getItemId($item)
    {
        if ($item instanceof Purchasable) {
            return $item->sku();
        } elseif ($item instanceof Item) {
            return $item->purchasable()->sku();
        }

        return $item;
    }

    /**
     * Determine whether the cart has a specific item.
     *
     * @param  mixed $item
     * @return bool
     */
    public function has($item): bool
    {
        return $this->items()->has($this->getItemId($item));
    }

    /**
     * Get the cart item.
     *
     * @param  mixed $item
     * @return \Jenky\Cartolic\Contracts\Cart\Item|null
     */
    public function get($item): ?Item
    {
        $id = $this->getItemId($item);

        return $this->items()->first(function ($cartItem) use ($id) {
            return $cartItem->purchasable()->sku() == $id;
        });
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
        // Find already added items that are identical to current selection.
        if ($item = $this->get($purchasable)) {
            // Update item item in cart.
            $this->storage->set([
                $item->purchasable()->sku() => $item->increment($quantity),
            ]);
        } else {
            // Otherwise, push it to the storage.
            $this->storage->push(
                $item = new CartItem($purchasable, $quantity)
            );
        }

        return $item;
    }

    /**
     * Remove an item from the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable $item
     * @return void
     */
    public function remove(Purchasable $item)
    {
        //
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

    /**
     * Convert the object to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'items' => $this->items()->toArray(),
            'subtotal' => $this->subtotal(),
            'total' => $this->total(),
        ];
    }

    /**
     * Convert the purchasable into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
