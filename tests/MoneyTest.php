<?php

namespace Jenky\Cartolic\Tests;

use Brick\Money\Money as BrickMoney;
use Illuminate\Foundation\Testing\WithFaker;
use Jenky\Cartolic\Cartolic;
use Jenky\Cartolic\Contracts\Cart;
use Jenky\Cartolic\Item;
use Jenky\Cartolic\Tests\Fixtures\Product;
use Money\Money;

class MoneyTest extends TestCase
{
    use WithFaker;

    public function test_using_brick_money()
    {
        Cartolic::calculateSubtotalUsing(function (Cart $cart) {
            return $cart->items()->reduce(function ($carry, Item $item) {
                return $carry->plus(BrickMoney::of($item->total(), 'USD'));
            }, BrickMoney::zero('USD'));
        });

        Cartolic::calculateTotalUsing(function (Cart $cart) {
            return $cart->subtotal();
        });

        $item = new Product($this->faker);

        $price = $item->price();
        $cart = $this->app[Cart::class];

        $cart->add($item, 5);

        $this->assertInstanceOf(BrickMoney::class, $cart->total());
        $this->assertTrue(BrickMoney::of($price * 5, 'USD')->isEqualTo($cart->subtotal()));
    }

    public function test_using_php_money()
    {
        Cartolic::calculateSubtotalUsing(function (Cart $cart) {
            return $cart->items()->reduce(function ($carry, Item $item) {
                return $carry->add(Money::USD($item->total()));
            }, Money::USD('0'));
        });

        Cartolic::calculateTotalUsing(function (Cart $cart) {
            return $cart->subtotal();
        });

        $item = new Product($this->faker);

        $price = $item->price();
        $cart = $this->app[Cart::class];

        $cart->add($item, 3);

        $this->assertInstanceOf(Money::class, $cart->total());
        $this->assertTrue(Money::USD($price * 3)->equals($cart->subtotal()));
    }
}
