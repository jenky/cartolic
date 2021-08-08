<?php

namespace Jenky\Cartolic;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;
use Jenky\Cartolic\Contracts\Purchasable;
use JsonSerializable;

class Item implements Arrayable, Jsonable, JsonSerializable
{
    use ForwardsCalls, Macroable {
        __call as macroCall;
    }

    /**
     * The purchasable item.
     *
     * @var \Jenky\Cartolic\Contracts\Purchasable
     */
    protected $purchasable;

    /**
     * The item quantity.
     *
     * @var int
     */
    protected $quantity = 1;

    /**
     * Create a new cart item instance.
     *
     * @param  \Jenky\Cartolic\Contracts\Purchasable  $purchasable
     * @param  int  $quantity
     * @return void
     */
    public function __construct(Purchasable $purchasable, int $quantity = 1)
    {
        $this->purchasable = $purchasable;

        if ($quantity <= 0) {
            throw new \InvalidArgumentException('The item quantity can\'t be smaller than 1.');
        }

        $this->quantity = $quantity;
    }

    /**
     * Get the purchasable item hash.
     *
     * @return string|int
     */
    // public function id()
    // {
    //     return $this->purchasable()->sku();
    // }

    /**
     * Get the item quantity.
     *
     * @return int
     */
    public function quantity(): int
    {
        return $this->quantity;
    }

    /**
     * Increment item quantity by a given amount.
     *
     * @param  int $amount
     * @return $this
     */
    public function increment(int $amount = 1)
    {
        $this->quantity += abs($amount);

        return $this;
    }

    /**
     * Decrement item quantity by a given amount.
     *
     * @param  int $amount
     * @return $this
     */
    public function decrement(int $amount = 1)
    {
        if ($amount >= $this->quantity()) {
            throw new \InvalidArgumentException('The item quantity can\'t be smaller than 1.');
        }

        $this->quantity -= abs($amount);

        return $this;
    }

    /**
     * Get the subtotal amount of the item.
     *
     * @return mixed
     */
    public function subtotal()
    {
        return $this->purchasable->price() * $this->quantity;
    }

    /**
     * Get the total amount of the item.
     *
     * @return mixed
     */
    public function total()
    {
        return $this->subtotal();
    }

    /**
     * Get the purchasable item.
     *
     * @return \Jenky\Cartolic\Contracts\Purchasable
     */
    public function purchasable(): Purchasable
    {
        return $this->purchasable;
    }

    /**
     * Convert the purchasable instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            // 'id' => $this->id(),
            'purchasable' => [
                'sku' => $this->purchasable->sku(),
                'name' => $this->purchasable->name(),
                'description' => $this->purchasable->description(),
                'price' => $this->purchasable->price(),
                'options' => $this->purchasable->options(),
            ],
            'quantity' => $this->quantity(),
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
     * Convert the purchasable instance to JSON.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Handle dynamic method calls into the method.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        $this->forwardCallTo($this->purchasable, $method, $parameters);
    }
}
