<?php

namespace Jenky\Cartolic\Tests;

use Cknow\Money\Money;
use Illuminate\Foundation\Testing\WithFaker;
use Jenky\Cartolic\Purchasable;

class Item
{
    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }

    public function toPurchasable()
    {
        return Purchasable::make($this)
            ->name($this->faker->macProcessor)
            ->description($this->faker->words(10, true))
            ->price(
                Money::USD($this->faker->randomNumber(2) * 1000)
            );
    }
}
