<?php

namespace Jenky\Cartolic\Tests\Storage;

use Brick\Math\RoundingMode;
use Brick\Money\Money as BrickMoney;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Jenky\Cartolic\Contracts\Cart\Cart;
use Jenky\Cartolic\Fee;
use Jenky\Cartolic\Money;
use Jenky\Cartolic\Purchasable;
use Jenky\Cartolic\Tests\TestCase;

abstract class StorageTestCase extends TestCase
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
                BrickMoney::ofMinor($this->faker->numberBetween(1000), 'USD') // Todo: use faker->currencyCode
            ));
    }

    public function test_get_all_items()
    {
        $items = \Jenky\Cartolic\Facades\Cart::items();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertTrue($items->isEmpty(), 'Cart items are not empty');
        $this->assertEmpty($items->toArray());
        $this->assertTrue(\Jenky\Cartolic\Facades\Cart::total()->isZero(), 'Cart total is not zero');
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
        $this->assertTrue($cart->total()->isPositive(), 'Cart total is not zero');

        $this->assertTrue($item->total()->isEqualTo($cart->subtotal()), 'Items total is equals to cart sub total');
    }

    public function test_remove_item()
    {
        $cart = $this->app->make(Cart::class);
        $item = $cart->add($this->item, 10);

        $cart->remove($item->purchasable(), 1);
        $this->assertCount(1, $cart->items());
        $this->assertEquals(9, $item->quantity());

        $cart->remove($item->purchasable(), 4);
        $this->assertCount(1, $cart->items());
        $this->assertEquals(5, $item->quantity());

        $cart->remove($item->purchasable());
        $this->assertCount(0, $cart->items());
        $this->assertEmpty($cart->items());
    }

    public function test_clear_cart()
    {
        $cart = $this->app->make(Cart::class);
        $cart->add($this->item, $this->faker->randomNumber(1));

        $cart->clear();
        $this->assertEmpty($cart->items());
        $this->assertTrue($cart->total()->isZero(), 'Cart total is not zero');
        $this->assertTrue($cart->fees()->amounts()->isZero(), 'Cart fees are not zero');
    }

    public function test_fees()
    {
        $cart = $this->app->make(Cart::class);
        $cart->add($this->item, $this->faker->randomNumber(1));

        $this->assertNotEmpty($cart->items());

        $discount = Fee::make('50% off', $cart->subtotal()->dividedBy(2, RoundingMode::DOWN)->negated());
        $cart->fees()->add($discount);

        $this->assertCount(1, $cart->fees()->all());
        $this->assertTrue($cart->total()->isPositive(), 'Cart total is not zero');
        $this->assertTrue($cart->fees()->amounts()->isNegative(), 'Cart fees are not zero');

        $shipping = Fee::make('shipping', new Money(BrickMoney::ofMinor(199, 'USD')));
        $cart->fees()->add($shipping);

        $this->assertCount(2, $cart->fees()->all());
        $this->assertTrue($cart->total()->isPositive(), 'Cart total is not zero');

        $this->assertTrue($cart->total()->isEqualTo(
            $cart->subtotal()->plus($cart->fees()->amounts())
        ), 'Cart total is equals to subtotal plus fees');
    }
}
