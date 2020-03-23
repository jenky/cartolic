<?php

namespace Jenky\Cartolic\Tests\Storage;

use Brick\Money\Money as BrickMoney;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Jenky\Cartolic\Contracts\Cart\Cart;
use Jenky\Cartolic\Money;
use Jenky\Cartolic\Purchasable;
use Jenky\Cartolic\Tests\TestCase;

abstract class CartStorageTestCase extends TestCase
{
    use WithFaker;

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
                BrickMoney::ofMinor($this->faker->numberBetween(1000), $this->faker->currencyCode)
            ));
    }

    public function test_get_all_items()
    {
        $items = \Jenky\Cartolic\Facades\Cart::items();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertTrue($items->isEmpty());
        $this->assertEmpty($items->toArray());
        $this->assertTrue(\Jenky\Cartolic\Facades\Cart::total()->isZero());
    }

    public function test_add_item_to_cart()
    {
        $cart = $this->app['cart'];
        $item = $cart->add($this->item);

        $this->assertNotEmpty($cart->items());
        $this->assertCount(1, $cart->items());
        $this->assertEquals(1, $item->quantity());

        $cart->add($item->purchasable(), 5);
        $this->assertCount(1, $cart->items());
        $this->assertEquals(6, $item->quantity());
        // $this->assertTrue($cart->total()->isPositive());

        $cart->clear();
        $this->assertEmpty($cart->items());
    }

    public function test_remove_item()
    {
        $cart = $this->app->make(Cart::class);
        $item = $cart->add($this->item, 10);

        $cart->remove($item->purchasable(), 1);
        $this->assertCount(1, $cart->items());
        $this->assertEquals(9, $item->quantity());

        $cart->remove($item->purchasable());
        $this->assertCount(0, $cart->items());
        $this->assertEmpty($cart->items());
    }
}
