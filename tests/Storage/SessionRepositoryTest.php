<?php

namespace Jenky\Cartolic\Tests\Storage;

class SessionRepositoryTest extends CartStorageTestCase
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
}
