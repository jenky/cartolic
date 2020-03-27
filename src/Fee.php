<?php

namespace Jenky\Cartolic;

use Jenky\Cartolic\Contracts\Fee\Fee as Contract;
use Jenky\Cartolic\Contracts\Money;

class Fee implements Contract
{
    /**
     * The fee ID.
     *
     * @var string|int
     */
    public $id;

    /**
     * The fee name.
     *
     * @var string
     */
    public $name;

    /**
     * The fee cost.
     *
     * @var \Jenky\Cartolic\Contracts\Money
     */
    public $cost;

    /**
     * Create new fee instance.
     *
     * @param  string|int $id
     * @param  \Jenky\Cartolic\Contracts\Money $money
     * @param  string|null $name
     * @return void
     */
    public function __construct($id, Money $money, ?string $name = null)
    {
        $this->id = $id;
        $this->cost = $money;
        $this->name = $name;
    }

    /**
     * Create new fee instance.
     *
     * @param  string|int $id
     * @param  \Jenky\Cartolic\Contracts\Money $money
     * @param  string|null $name
     * @return static
     */
    public static function make($id, Money $money, ?string $name = null)
    {
        return new static($id, $money, $name);
    }

    /**
     * Get the fee unique ID.
     *
     * @return string|int
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
