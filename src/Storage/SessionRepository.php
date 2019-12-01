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
        return $this->session->set(
            $this->storageKey, $value
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
}
