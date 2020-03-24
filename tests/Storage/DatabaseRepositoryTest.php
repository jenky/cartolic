<?php

namespace Jenky\Cartolic\Tests\Storage;

class DatabaseRepositoryTest extends StorageTestCase
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
}
