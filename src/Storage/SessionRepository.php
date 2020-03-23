<?php

namespace Jenky\Cartolic\Storage;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;
use Jenky\Cartolic\Contracts\Storage\StorageRepository;

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
     * @param  \Illuminate\Contracts\Session\Session $session
     * @param  string $storageKey
     * @return void
     */
    public function __construct(Session $session, string $storageKey)
    {
        $this->session = $session;
        $this->storageKey = $storageKey;
    }

    /**
     * Get all of the cart storage items for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        return Collection::make($this->session->get(
            $this->storageKey, []
        ));
    }

    /**
     * Set a given cart storage value.
     *
     * @param  mixed $value
     * @return void
     */
    public function set($value)
    {
        return $this->session->put(
            $this->storageKey, $value
        );
    }

    /**
     * Remove a given cart storage value.
     *
     * @param  mixed $value
     * @return mixed
     */
    public function remove($value)
    {
        return $this->session->pull(
            $this->storageKey.'.'.$value
        );
    }

    /**
     * Prepend a value onto an array cart storage value.
     *
     * @param  mixed $value
     * @return void
     */
    public function prepend($value)
    {
    }

    /**
     * Push a value onto an array cart storage value.
     *
     * @param  mixed $value
     * @return void
     */
    public function push($value)
    {
        return $this->session->push(
            $this->storageKey, $value
        );
    }

    /**
     * Remove all of the items from the cart storage.
     *
     * @return void
     */
    public function flush()
    {
        $this->session->forget($this->storageKey);
    }
}
