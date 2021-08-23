<?php

namespace Jenky\Cartolic;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;
use IteratorAggregate;
use Jenky\Cartolic\Contracts\Cart as Contract;
use Jenky\Cartolic\Contracts\Fee\Collector;
use Jenky\Cartolic\Contracts\Purchasable;
use Jenky\Cartolic\Contracts\StorageRepository;
use Jenky\Cartolic\Events\CartCleared;
use Jenky\Cartolic\Events\ItemAdded;
use Jenky\Cartolic\Events\ItemRemoved;
use Jenky\Cartolic\Events\ItemUpdated;
use JsonSerializable;

class Cart implements Contract, Arrayable, Jsonable, JsonSerializable, Countable, IteratorAggregate
{
    use Concerns\HasEvents;
    use Macroable;
    use Tappable;

    /**
     * The storage driver instance.
     *
     * @var \Jenky\Cartolic\Contracts\StorageRepository
     */
    protected $storage;

    /**
     * The cart items.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $items;

    /**
     * The shopping cart fees.
     *
     * @var \Jenky\Cartolic\Contracts\Fee\Collector
     */
    protected $fees;

    /**
     * Create a new cart instance.
     *
     * @param  \Jenky\Cartolic\Contracts\StorageRepository  $storage
     * @return void
     */
    public function __construct(StorageRepository $storage)
    {
        $this->storage = $storage;
        $this->items = $storage->get();
    }

    /**
     * Get all the fees.
     *
     * @return \Jenky\Cartolic\Contracts\Fee\Collector
     */
    // public function fees(): Collector
    // {
    //     return $this->fees;
    // }

    /**
     * Get all the cart items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function items(): Collection
    {
        return $this->items;
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->items()->getIterator();
    }

    /**
     * Get the number of items for the current cart.
     *
     * @return int
     */
    public function count()
    {
        return $this->items()->count();
    }

    /**
     * Get cart subtotal.
     *
     * @return mixed
     */
    public function subtotal()
    {
        if ($callback = Cartolic::$calculateSubtotalUsing) {
            return $callback($this);
        }

        return $this->items()->reduce(function ($carry, Item $item) {
            return $carry + ($item->purchasable()->price() * $item->quantity());
        }, 0);
    }

    /**
     * Get cart total.
     *
     * @return mixed
     */
    public function total()
    {
        if ($callback = Cartolic::$calculateTotalUsing) {
            return $callback($this);
        }

        return $this->subtotal(); //+ $this->fees()->total();
    }

    /**
     * Resolve the item id.
     *
     * @param  mixed  $item
     * @return string|int
     */
    protected function itemId($item)
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
     * @param  mixed  $item
     * @return bool
     */
    public function has($item): bool
    {
        return $this->items()->has(
            $this->itemId($item)
        );
    }

    /**
     * Get the cart item.
     *
     * @param  mixed  $item
     * @return \Jenky\Cartolic\Contracts\Cart\Item|null
     */
    public function get($item): ?Item
    {
        return $this->items()->get(
            $this->itemId($item)
        );
    }

    /**
     * Add an item to the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable  $item
     * @param  int  $quantity
     * @return bool
     */
    public function add(Purchasable $purchasable, int $quantity = 1)
    {
        if ($this->has($purchasable)) {
            $item = $this->get($purchasable)->increment($quantity);
            $event = new ItemUpdated($item);
        } else {
            $item = new Item($purchasable, $quantity);
            $event = new ItemAdded($item);
        }

        $this->items()->put($this->itemId($item), $item);

        $this->event($event);

        return true;
    }

    /**
     * Remove an item from the cart.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable  $item
     * @param  int|null  $quantity
     * @return bool
     */
    public function remove(Purchasable $item, ?int $quantity = null)
    {
        if (! $this->has($item)) {
            return false;
        }

        $item = $this->get($item);
        $id = $this->itemId($item);

        if (is_null($quantity) || $quantity > $item->quantity()) {
            // Remove the item from the cart.
            $this->items()->forget($id);

            $event = new ItemRemoved($item);
        } else {
            $this->items()->put(
                $id, $item->decrement($quantity)
            );

            $event = new ItemUpdated($item);
        }

        $this->event($event);

        return true;
    }

    /**
     * Clear the cart.
     *
     * @return void
     */
    public function clear()
    {
        $this->items = collect([]);

        $this->storage->flush();

        $this->event(new CartCleared($this));
    }

    /**
     * Save the cart to the persistent storage.
     *
     * @return mixed
     */
    public function save()
    {
        $this->storage->store($this->items());
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
            // 'fees' => $this->fees()->toArray(),
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
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
