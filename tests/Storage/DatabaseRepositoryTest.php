<?php

namespace Jenky\Cartolic\Tests\Storage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Jenky\Cartolic\Contracts\StorageRepository;
use Jenky\Cartolic\Facades\Cart;
use Jenky\Cartolic\Storage\DatabaseRepository;
use Jenky\Cartolic\Tests\Fixtures\Product;
use Jenky\Cartolic\Tests\TestCase;

class DatabaseRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('cart.driver', 'database');

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    public function test_storage_instance()
    {
        $this->assertInstanceOf(DatabaseRepository::class, $this->app[StorageRepository::class]);
    }

    public function test_store_cart()
    {
        Cart::add(new Product($this->faker), 4);
        Cart::save();

        $connection = $this->app['config']->get('cart.storage.database.connection');
        $table = $this->app['config']->get('cart.storage.database.table');

        $this->assertDatabaseHas($table, [
            'user_id' => null,
        ], $connection);
    }
}
