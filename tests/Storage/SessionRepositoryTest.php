<?php

namespace Jenky\Cartolic\Tests\Storage;

use Jenky\Cartolic\Tests\TestCase;

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

    public function test_storage_instance()
    {
        $this->assertInstanceOf(SessionRepository::class, $this->app[StorageRepository::class]);
    }
}
