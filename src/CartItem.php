<?php

namespace Jenky\Cartolic;

use Cknow\Money\Money;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;
use Jenky\Cartolic\Purchasable;
use JsonSerializable;

class CartItem implements Arrayable, Jsonable, JsonSerializable
{
    use ForwardsCalls, Macroable {
        __call as macroCall;
    }

    /**
     * The purchasable item.
     *
     * @var \Jenky\Cartolic\Purchasable
     */
    protected $purchasable;

    /**
     * The item Id.
     *
     * @var string
     */
    public $id;

    /**
     * The item quantity.
     *
     * @var int
     */
    public $quantity = 1;

    /**
     * Create a new cart item instance.
     *
     * @param  \Jenky\Cartolic\Purchasable $purchasable
     * @param  int $quantity
     * @return void
     */
    public function __construct(Purchasable $purchasable, int $quantity = 1)
    {
        $this->purchasable = $purchasable;

        $this->id = (string) Str::orderedUuid();

        $this->quantity($quantity, true);
    }

    /**
     * Assign the item quantity.
     *
     * @param  int $quantity
     * @param  bool $force
     * @return $this
     */
    public function quantity(int $quantity, $force = false)
    {
        if ($force) {
            $this->quantity = $quantity;
        } else {
            $this->quantity += $quantity;
        }

        return $this;
    }

    /**
     * Get the total amount of the item.
     *
     * @return \Cknow\Money\Money
     */
    public function total(): Money
    {
        return $this->purchasable->price->multiply(
            $this->quantity
        );
    }

    /**
     * Convert the purchasable instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'purchasable' => $this->purchasable->toArray(),
            'total' => $this->total()->toArray(),
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
