<?php

namespace Jenky\Cartolic\Test\Storage;

use Illuminate\Support\Collection;
use Jenky\Cartolic\Test\Item;
use Jenky\Cartolic\Test\TestCase;

abstract class CartStorageTestCase extends TestCase
{
    public function test_get_all_items()
    {
        $items = $this->app['cart']->items();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertTrue($items->isEmpty());
        $this->assertEmpty($items->toArray());
    }

    public function test_add_item_to_cart()
    {
        $cart = $this->app['cart'];
        $item = new Item;

        $cart->add($item->toPurchasable());

        $this->assertNotEmpty($cart->items());
        $this->assertCount(1, $cart->items());
    }
}
