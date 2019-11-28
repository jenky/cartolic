<?php

namespace Jenky\Cartolic\Test\Storage;

use Illuminate\Support\Collection;
use Jenky\Cartolic\Test\TestCase;

class SessionRepositoryTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('cart.driver', 'session');
    }

    public function test_get_all_items()
    {
        $items = $this->app['cart']->items();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertTrue($items->isEmpty());
        $this->assertEmpty($items->toArray());
    }
}
