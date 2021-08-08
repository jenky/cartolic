<?php

namespace Jenky\Cartolic\Contracts;

interface Purchasable
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
     * @return string|null
     */
    public function description(): ?string;

    /**
     * Get the purchasable item price.
     *
     * @return mixed
     */
    public function price();

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
