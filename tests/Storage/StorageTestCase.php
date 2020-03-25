<?php

namespace Jenky\Cartolic\Tests\Storage;

use Brick\Money\Money as BrickMoney;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Jenky\Cartolic\Money;
use Jenky\Cartolic\Purchasable;
use Jenky\Cartolic\Tests\TestCase;

abstract class StorageTestCase extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * The purchasable item.
     *
     * @var \Jenky\Cartolic\Contracts\Purchasable
     */
    protected $item;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->item = Purchasable::make()
            ->withName($this->faker->macProcessor)
            ->withDescription($this->faker->words(10, true))
            ->withPrice(new Money(
                BrickMoney::ofMinor($this->faker->numberBetween(1000), 'USD') // Todo: use faker->currencyCode
            ));
    }
}
