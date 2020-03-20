<?php

namespace Jenky\Cartolic;

use Brick\Money\Money as 💸;
use Illuminate\Support\Traits\Macroable;
use Jenky\Cartolic\Contracts\Money;
use Jenky\Cartolic\Contracts\Purchasable as Contract;

class Purchasable implements Contract
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
     * @var \Jenky\Cartolic\Contracts\Money
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
     * The purchasable item hash.
     *
     * @var string
     */
    protected $hash;

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
        // $this->price = new \Jenky\Cartolic\Money(💸::zero('USD'));
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
    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the item name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Assign the purchasable item description.
     *
     * @param  string $description
     * @return $this
     */
    public function withDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the purchasable item description.
     *
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * Assign the purchasable item price.
     *
     * @param  \Jenky\Cartolic\Contracts\Money $price
     * @return $this
     */
    public function withPrice(Money $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the purchasable item price.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function price(): Money
    {
        return $this->price;
    }

    /**
     * Assign the purchasable item options.
     *
     * @param  string $options
     * @return $this
     */
    public function withOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get the purchasable item options.
     *
     * @return array
     */
    public function options(): array
    {
        return [];
    }

    /**
     * Assign the purchasable item metadata.
     *
     * @param  string $metadata
     * @return $this
     */
    public function withMetadata(array $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get the purchasable item metadata.
     *
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }

    /**
     * Get the purchasable item hash.
     *
     * @return string
     */
    public function hash()
    {
        if (is_null($this->hash)) {
            $this->hash = md5($this->toJson());
        }

        return $this->hash;
    }

    /**
     * Convert the purchasable instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description(),
            'price' => $this->price->toArray(),
            'options' => $this->options(),
            'metadata' => $this->metadata(),
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
