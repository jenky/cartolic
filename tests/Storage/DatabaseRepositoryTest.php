<?php

namespace Jenky\Cartolic\Tests\Storage;

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
    }

    public function test_example()
    {
        return $this->assertTrue(true);
    }
}
