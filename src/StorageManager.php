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
            $this->config('cart.storage.database.connection'),
            $this->config('cart.storage.database.table'),
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
            $this->app->make('session')->driver($this->config('cart.storage.session.driver')),
            $this->config('cart.storage.session.storage_key')
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
