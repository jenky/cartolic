<?php

namespace Jenky\Cartolic\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Jenky\Cartolic\Contracts\Cart;
use Jenky\Cartolic\Events\ItemAdded;
use Jenky\Cartolic\Events\ItemRemoved;
use Jenky\Cartolic\Events\ItemUpdated;
use Jenky\Cartolic\Facades\Cart as Facade;
use Jenky\Cartolic\Tests\Fixtures\Product;

class CartTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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

        $this->item = new Product($this->faker);
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->app['cart']->clear();

        parent::tearDown();
    }

    public function test_get_all_items()
    {
        $items = Facade::items();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertTrue($items->isEmpty(), 'Cart is empty');
        $this->assertEmpty($items->toArray());
        $this->assertTrue(Facade::total() === 0, 'Cart total is zero');
    }

    public function test_add_item_to_cart()
    {
        $cart = $this->app['cart'];
        $cart->add($this->item);
        $item = $cart->get($this->item);

        $this->assertNotEmpty($cart->items());
        $this->assertCount(1, $cart->items());
        $this->assertEquals(1, $item->quantity());

        $cart->add($this->item, 5);
        $this->assertCount(1, $cart->items());
        $this->assertEquals(6, $item->quantity());
        $this->assertTrue($cart->total() > 0, 'Cart total is not zero');

        $this->assertTrue($item->total() === $cart->subtotal(), 'Items total is equals to cart sub total');

        $cart->clear();

        $this->expectException(\InvalidArgumentException::class);
        $cart->add($this->item, -3);
    }

    public function test_remove_item()
    {
        $cart = $this->app->make(Cart::class);
        $cart->add($this->item, 10);
        $item = $cart->get($this->item);

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
        $cart->add($this->item, $this->faker->numberBetween(1, 10));

        $cart->clear();
        $this->assertEmpty($cart->items());
        $this->assertTrue($cart->items()->isEmpty());
        $this->assertTrue($cart->total() === 0, 'Cart total is zero');
    }

    // public function test_fees()
    // {
    //     $cart = $this->app->make('cart');
    //     $cart->add($this->item, $this->faker->numberBetween(1, 10));

    //     $this->assertNotEmpty($cart->items());

    //     $discount = Fee::make('50% off', $cart->subtotal()->dividedBy(2, RoundingMode::DOWN)->negated());
    //     $cart->fees()->add($discount);

    //     $this->assertCount(1, $cart->fees()->all());
    //     $this->assertTrue($cart->total()->isPositive(), 'Cart total is not zero');
    //     $this->assertTrue($cart->fees()->amounts()->isNegative(), 'Cart fees are not zero');

    //     $shipping = Fee::make('shipping', new Money(BrickMoney::ofMinor(199, 'USD')));
    //     $cart->fees()->add($shipping);

    //     $this->assertCount(2, $cart->fees()->all());
    //     $this->assertTrue($cart->total()->isPositive(), 'Cart total is not zero');

    //     $this->assertTrue($cart->total()->isEqualTo(
    //         $cart->subtotal()->plus($cart->fees()->amounts())
    //     ), 'Cart total is equals to subtotal plus fees');
    // }

    public function test_events()
    {
        $fake = Event::fake();

        $cart = $this->app->make('cart');
        $cart->setEventDispatcher($fake);

        $cart->add($this->item);

        Event::assertDispatched(ItemAdded::class);

        $cart->add($this->item, 5);
        $cart->remove($this->item, 2);

        Event::assertDispatched(ItemUpdated::class, 2);

        $cart->remove($this->item);

        Event::assertDispatched(ItemRemoved::class);
    }
}
