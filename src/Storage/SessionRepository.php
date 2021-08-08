<?php

namespace Jenky\Cartolic\Storage;

use Illuminate\Contracts\Session\Session;
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
     * Store the given items.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @return void
     */
    public function store(Collection $items)
    {
        return $this->session->put(
            $this->storageKey, $items
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
