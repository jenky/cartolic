<?php

namespace Jenky\Cartolic\Contracts\Fee;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Jenky\Cartolic\Contracts\Money;
use JsonSerializable;

interface Collector extends Arrayable, Jsonable, JsonSerializable
{
    /**
     * Get all the fees.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Add a new fee.
     *
     * @param  \Jenky\Cartolic\Contracts\Fee\Fee $fee
     * @return mixed
     */
    public function add(Fee $fee);

    /**
     * Get a fee.
     *
     * @param  string|int $fee
     * @return \Jenky\Cartolic\Contracts\Fee\Fee|null
     */
    public function get($fee): ?Fee;

    /**
     * Get total amounts of all fees.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function amounts(): Money;
}
