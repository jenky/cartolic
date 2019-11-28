<?php

namespace Jenky\Cartolic\Test\Storage;

use Jenky\Cartolic\Test\TestCase;

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
}
