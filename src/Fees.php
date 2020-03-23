<?php

namespace Jenky\Cartolic;

use Illuminate\Support\Str;
use Jenky\Cartolic\Contracts\Fee\Collector;
use Jenky\Cartolic\Contracts\Fee\Fee;

class Fees implements Collector
{
    /**
     * The list of fees.
     *
     * @var array
     */
    protected $fees = [];

    /**
     * Get all the fees.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->fees;
    }

    /**
     * Add a new fee.
     *
     * @param  \Jenky\Cartolic\Contracts\Fee\Fee $fee
     * @return self
     */
    public function add(Fee $fee)
    {
        $this->fees[$this->normalizeFeeName($fee->name())] = $fee;

        return $this;
    }

    /**
     * Get a fee.
     *
     * @param  string|int $fee
     * @return \Jenky\Cartolic\Contracts\Fee\Fee|null
     */
    public function get($fee): ?Fee
    {
        return $this->fees[$this->normalizeFeeName($fee)] ?? null;
    }

    /**
     * Get total amounts of all fees.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function amounts(): Money
    {
        return collect($this->all())->reduce(function ($carry, Fee $fee) {
            return $carry->plus($fee->cost()->toMoney());
        }, new \Jenky\Cartolic\Money(\Brick\Money\Money::zero('USD')));
    }

    /**
     * Normalize fee name to use as array key.
     *
     * @param  string $name
     * @return string
     */
    protected function normalizeFeeName($name): string
    {
        return Str::snake($name);
    }

    /**
     * Convert the purchasable instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->all();
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
