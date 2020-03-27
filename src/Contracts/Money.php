<?php

namespace Jenky\Cartolic\Contracts;

use JsonSerializable;

interface Money extends JsonSerializable
{
    /**
     * Get the amount.
     *
     * @return string|int|float
     */
    public function amount();

    /**
     * Get the currency.
     *
     * @return string
     */
    public function currency();

    /**
     * Format the money.
     *
     * @param  \NumberFormatter $formatter
     * @return string
     */
    public function format(\NumberFormatter $formatter): string;
}
