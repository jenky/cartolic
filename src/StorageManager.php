<?php

namespace Jenky\Cartolic;

use Illuminate\Support\Manager;
use Jenky\Cartolic\Storage\DatabaseRepository;
use Jenky\Cartolic\Storage\SessionRepository;

class StorageManager extends Manager
{
    /**
     * Get the config instance or value.
     *
     * @param  string|null $key
     * @param  mixed $default
     * @return mixed
     */
    protected function config(?string $key = null, $default = null)
    {
        $config = property_exists($this, 'config')
            ? $this->config
            : $this->app->make('config');

        return $key ? $config->get($key, $default) : $config;
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
            $this->config('cart.storage.database.table'),
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
        return $this->app->make('auth')->guard(
            $this->config('cart.storage.database.guard')
        );
    }

    /**
     * Get the database connection for the database driver.
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    protected function getDatabaseConnection()
    {
        return $this->app->make('db')->connection(
            $this->config('cart.storage.database.connection')
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
            $this->config('cart.storage.session.storage_key')
        );
    }

    /**
     * Get the session underlying driver handler for the session driver.
     *
     * @return \Illuminate\Contracts\Session\Session
     */
    protected function getSessionDriver()
    {
        return $this->app->make('session')->driver(
            $this->config('cart.storage.session.driver')
        );
    }

    /**
     * Get the default Cartolic driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config('cart.driver');
    }
}
