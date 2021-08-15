<?php

namespace Jenky\Cartolic\Tests\Storage;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Jenky\Cartolic\Contracts\StorageRepository;
use Jenky\Cartolic\Facades\Cart;
use Jenky\Cartolic\Storage\SessionRepository;
use Jenky\Cartolic\StorageManager;
use Jenky\Cartolic\Tests\Fixtures\Product;
use Jenky\Cartolic\Tests\TestCase;

class SessionRepositoryTest extends TestCase
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

        $this->app['config']->set('cart.driver', 'session');
    }

    public function test_storage_instance()
    {
        $this->assertInstanceOf(SessionRepository::class, $this->app[StorageRepository::class]);
    }

    public function test_store_cart()
    {
        Cart::add(new Product($this->faker), 4);
        Cart::save();

        $session = $this->app[StorageManager::class]->getSessionDriver();
        $key = $this->app['config']->get('cart.storage.session.storage_key', 'cartolic');

        $this->assertTrue($session->has($key));

        $content = $session->get($key);

        $this->assertInstanceOf(Collection::class, $content);
        $this->assertCount(1, $content);
    }
}
