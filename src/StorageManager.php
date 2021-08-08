<?php

namespace Jenky\Cartolic;

use Illuminate\Support\Manager;
use Jenky\Cartolic\Contracts\StorageRepository;
use Jenky\Cartolic\Storage\DatabaseRepository;
use Jenky\Cartolic\Storage\SessionRepository;

class StorageManager extends Manager
{
    /**
     * Get a driver instance.
     *
     * @param  string|null  $driver
     * @throws \InvalidArgumentException
     * @return \Jenky\Cartolic\Contracts\StorageRepository
     */
    public function driver($driver = null)
    {
        $driver = parent::driver($driver);

        if (! $driver instanceof StorageRepository) {
            throw new \InvalidArgumentException('The cart storage repository must implement '.StorageRepository::class);
        }

        return $driver;
    }

    /**
     * Create a Database storage instance.
     *
     * @return \Jenky\Cartolic\Storage\DatabaseRepository
     */
    public function createDatabaseDriver()
    {
        return new DatabaseRepository(
            $this->getDatabaseConnection(),
            $this->config->get('cart.storage.database.table'),
            $this->getAuthGuard()
        );
    }

    /**
     * Get the authentication guard for the database driver.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function getAuthGuard()
    {
        return $this->container->make('auth')->guard(
            $this->config->get('cart.storage.database.guard')
        );
    }

    /**
     * Get the database connection for the database driver.
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    protected function getDatabaseConnection()
    {
        return $this->container->make('db')->connection(
            $this->config->get('cart.storage.database.connection')
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
            $this->getSessionDriver(),
            $this->config->get('cart.storage.session.storage_key', 'cartolic')
        );
    }

    /**
     * Get the session underlying driver handler for the session driver.
     *
     * @return \Illuminate\Contracts\Session\Session
     */
    protected function getSessionDriver()
    {
        return $this->container->make('session')->driver(
            $this->config->get('cart.storage.session.driver')
        );
    }

    /**
     * Get the default Cartolic driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config->get('cart.driver');
    }
}
