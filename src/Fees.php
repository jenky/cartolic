<?php

namespace Jenky\Cartolic;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use Jenky\Cartolic\Contracts\Fee\Collector;
use Jenky\Cartolic\Contracts\Fee\Fee;
use JsonSerializable;

class Fees implements Collector, Arrayable, Jsonable, JsonSerializable
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
        $this->fees[$this->normalizeFeeId($fee->id())] = $fee;

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
        return $this->fees[$this->normalizeFeeId($fee)] ?? null;
    }

    /**
     * Get total amounts of all fees.
     *
     * @return mixed
     */
    public function total()
    {
        return collect($this->all())->reduce(function ($carry, Fee $fee) {
            return $carry + $fee->amount();
        }, 0);
    }

    /**
     * Normalize fee id to use as array key.
     *
     * @param  string $id
     * @return string
     */
    protected function normalizeFeeId($id): string
    {
        return Str::snake($id);
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
