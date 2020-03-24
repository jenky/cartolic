<?php

namespace Jenky\Cartolic\Contracts\Fee;

use Jenky\Cartolic\Contracts\Money;

interface Fee
{
    /**
     * Get the fee unique ID.
     *
     * @return string
     */
    public function id();

    /**
     * Get the fee name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Get the fee cost.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function cost(): Money;
}
