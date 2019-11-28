<?php

namespace Jenky\Cartolic\Storage;

use Illuminate\Session\Store;
use Illuminate\Support\Collection;
use Jenky\Cartolic\Contracts\StorageRepository;

class SessionRepository implements StorageRepository
{
    /**
     * The session storage that should be used.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * The storage session key.
     *
     * @var string
     */
    protected $storageKey;

    /**
     * Create new Session repository instance.
     *
     * @param  \Illuminate\Session\Store $session
     * @param  string $storageKey
     * @return void
     */
    public function __construct(Store $session, $storageKey)
    {
        $this->session = $session;
        $this->storageKey = $storageKey;
    }

    /**
     * Get the storage key.
     *
     * @param  string|null $key
     * @return string
     */
    protected function storageKey($key = null)
    {
        return $key ? $this->storageKey.'.'.$key : $this->storageKey;
    }

    /**
     * Determine if the given cart storage value exists.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key): bool
    {
        return $this->session->has(
            $this->storageKey($key)
        );
    }

    /**
     * Get the specified cart storage value.
     *
     * @param  array|string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->session->get(
            $this->storageKey($key), $default
        );
    }

    /**
     * Get all of the cart storage items for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        return Collection::make($this->session->get(
            $this->storageKey(), []
        ));
    }

    /**
     * Set a given cart storage value.
     *
     * @param  array|string $key
     * @param  mixed $value
     * @return void
     */
    public function set($key, $value = null)
    {
        return $this->session->set(
            $this->storageKey($key), $value
        );
    }

    /**
     * Prepend a value onto an array cart storage value.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function prepend($key, $value)
    {
    }

    /**
     * Push a value onto an array cart storage value.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function push($key, $value)
    {
    }
}
