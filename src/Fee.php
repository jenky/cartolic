<?php

namespace Jenky\Cartolic;

use Jenky\Cartolic\Contracts\Fee\Fee as Contract;
use Jenky\Cartolic\Contracts\Money;

class Fee implements Contract
{
    public $name;

    public $cost;

    public function __construct(string $name, Money $money)
    {
        $this->name = $name;
        $this->cost = $money;
    }

    public static function make(string $name, Money $money)
    {
        return new static($name, $money);
    }

    /**
     * Get the fee name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the fee cost.
     *
     * @return \Jenky\Cartolic\Contracts\Money
     */
    public function cost(): Money
    {
        return $this->cost;
    }
}
