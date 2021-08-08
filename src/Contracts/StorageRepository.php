<?php

namespace Jenky\Cartolic\Contracts;

use Illuminate\Support\Collection;

interface StorageRepository
{
    /**
     * Get all of the cart items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection;

    /**
     * Store the given items.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @return void
     */
    public function store(Collection $items);

    /**
     * Remove all of the items from the cart storage.
     *
     * @return void
     */
    public function flush();
}
