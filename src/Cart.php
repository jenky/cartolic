<?php

namespace Jenky\Cartolic;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Jenky\Cartolic\Contracts\Cart\Cart as Contract;
use Jenky\Cartolic\Contracts\Cart\Item;
use Jenky\Cartolic\Contracts\Fee\Collector;
use Jenky\Cartolic\Contracts\Money;
use Jenky\Cartolic\Contracts\Purchasable;
use Jenky\Cartolic\Contracts\Storage\StorageRepository;

class Cart implements Contract
{
    use Macroable;

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The storage driver instance.
     *
     * @var \Jenky\Cartolic\Contracts\StorageRepository
     */
    protected $storage;

    /**
     * The shopping cart fees.
     *
     * @var \Jenky\Cartolic\Contracts\Fee\Collector
     */
    protected $fees;

    /**
     * Create a new cart instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->storage = $app->make(StorageRepository::class);
        $this->fees = $app->make(Collector::class);
    }

    /**
     * Get all the fees.
     *
     * @return \Jenky\Cartolic\Contracts\Fee\Collector
     */
    public function fees(): Collector
    {
        return $this->fees;
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
            return $carry->plus($item->total());
        }, \Jenky\Cartolic\Money::zero());
    }

    /**
     * Get cart total.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function total(): Money
    {
        return $this->subtotal()->plus(
            $this->fees()->amounts()
        );
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
        return $this->items()->get($this->getItemId($item));
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
        if ($this->has($purchasable)) {
            $item = $this->get($purchasable)->increment($quantity);
        } else {
            // $item = new CartItem($purchasable, $quantity);
            $item = $this->app->bound(Item::class)
                ? $this->app->make(Item::class, compact('purchasable', 'quantity'))
                : new CartItem($purchasable, $quantity);
        }

        $this->storage->set([
            $this->getItemId($item) => $item,
        ]);

        return $item;
    }

    /**
     * Remove an item from the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable $item
     * @param  int|null $quantity
     * @return \Jenky\Cartolic\Contracts\Item|null
     */
    public function remove(Purchasable $item, ?int $quantity = null): ?Item
    {
        if (! $this->has($item)) {
            return null;
        }

        $item = $this->get($item);
        $id = $this->getItemId($item);

        if (is_null($quantity) || $quantity > $item->quantity()) {
            // Remove the item from the cart.
            $this->storage->remove($id);

            return null;
        }

        $item->decrement($quantity);

        $this->storage->set([
            $id => $item,
        ]);

        return $item;
    }

    /**
     * Clear the cart.
     *
     * @return void
     */
    public function clear()
    {
        $this->storage->flush();
    }

    /**
     * Convert the object to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'items' => $this->items()->values()->toArray(),
            'fees' => $this->fees()->toArray(),
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
