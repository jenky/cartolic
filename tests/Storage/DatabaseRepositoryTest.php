<?php

namespace Jenky\Cartolic\Tests\Storage;

use Jenky\Cartolic\Contracts\StorageRepository;
use Jenky\Cartolic\Storage\DatabaseRepository;
use Jenky\Cartolic\Tests\TestCase;

class DatabaseRepositoryTest extends TestCase
{
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
}
