<?php

namespace Jenky\Cartolic\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use JsonSerializable;

interface Money extends Arrayable, Jsonable, JsonSerializable, Renderable
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
