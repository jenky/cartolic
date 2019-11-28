<?php

namespace Jenky\Cartolic;

use Illuminate\Support\Manager;
use Jenky\Cartolic\Storage\DatabaseRepository;
use Jenky\Cartolic\Storage\SessionRepository;

class StorageManager extends Manager
{
    /**
     * Create a Database storage instance.
     *
     * @return \Jenky\Cartolic\Storage\DatabaseRepository
     */
    public function createDatabaseDriver()
    {
        return new DatabaseRepository(
            $this->app['config']->get('cart.storage.database.connection')
        );
    }

    /**
     * Create a Session storage instance.
     *
     * @return \Jenky\Cartolic\Storage\SessionRepository
     */
    public function createSessionDriver()
    {
        return new SessionRepository(
            $this->app['session']->driver($this->app['config']->get('cart.storage.session.driver')),
            $this->app['config']->get('cart.storage.session.storage_key')
        );
    }

    /**
     * Get the default Cartolic driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['cart.driver'];
    }
}
