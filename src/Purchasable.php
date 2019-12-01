<?php

namespace Jenky\Cartolic;

use Cknow\Money\Money;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;

class Purchasable implements Arrayable, Jsonable, JsonSerializable
{
    use Macroable;

    /**
     * The purchasable item name.
     *
     * @var string
     */
    public $name;

    /**
     * The purchasable item description.
     *
     * @var string
     */
    public $description;

    /**
     * The purchasable item price.
     *
     * @var \Cknow\Money\Money
     */
    public $price;

    /**
     * The purchasable item options.
     *
     * @var array
     */
    public $options = [];

    /**
     * The purchasable item metadata.
     *
     * @var array
     */
    public $metadata = [];

    /**
     * The purchasable resource.
     *
     * @var mixed
     */
    protected $resource;

    /**
     * Create a new purchasable instance.
     *
     * @param  mixed $resource
     * @return void
     */
    public function __construct($resource = null)
    {
        $this->resource = $resource;

        // Assign default price.
        $this->price = money(0);
    }

    /**
     * Create a new entry instance.
     *
     * @param  mixed $resource
     * @return static
     */
    public static function make($resource = null)
    {
        return new static($resource);
    }

    /**
     * Assign the purchasable item name.
     *
     * @param  string $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Assign the purchasable item description.
     *
     * @param  string $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Assign the purchasable item price.
     *
     * @param  \Cknow\Money\Money $price
     * @return $this
     */
    public function price(Money $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Assign the purchasable item options.
     *
     * @param  string $options
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Assign the purchasable item metadata.
     *
     * @param  string $metadata
     * @return $this
     */
    public function metadata(array $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Convert the purchasable instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'item' => $this->item,
            'description' => $this->description,
            'price' => $this->price->toArray(),
            'options' => $this->options,
            'metadata' => $this->metadata,
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
}
