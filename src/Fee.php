<?php

namespace Jenky\Cartolic;

use Jenky\Cartolic\Contracts\Fee\Fee as Contract;
use Jenky\Cartolic\Contracts\Money;

class Fee implements Contract
{
    public $id;

    public $name;

    public $cost;

    public function __construct($id, Money $money, ?string $name = null)
    {
        $this->id = $id;
        $this->cost = $money;
        $this->name = $name;
    }

    public static function make(string $name, Money $money)
    {
        return new static($name, $money);
    }

    /**
     * Get the fee unique ID.
     *
     * @return string
     */
    public function id()
    {
        return $this->id;
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
