<?php

namespace Jenky\Cartolic\Contracts\Cart;

use Jenky\Cartolic\Contracts\Money;

interface Fee
{
    public function name(): string;

    public function cost(): Money;
}
