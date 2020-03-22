<?php

namespace Jenky\Cartolic\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

interface Purchasable extends Arrayable, Jsonable, JsonSerializable
{
    /**
     * Get the purchasable item hash.
     *
     * @return string|int
     */
    public function sku();

    /**
     * Get the purchasable item name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Get the purchasable item description.
     *
     * @return string
     */
    public function description(): string;

    /**
     * Get the purchasable item price.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function price(): Money;

    /**
     * Get the purchasable item options.
     *
     * @return array
     */
    public function options(): array;

    /**
     * Get the purchasable item metadata.
     *
     * @return array
     */
    public function metadata(): array;
}
