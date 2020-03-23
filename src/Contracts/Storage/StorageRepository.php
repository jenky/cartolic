<?php

namespace Jenky\Cartolic\Contracts\Storage;

use Illuminate\Support\Collection;

interface StorageRepository
{
    /**
     * Get all of the cart storage items for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection;

    /**
     * Set a given cart storage value.
     *
     * @param  mixed $value
     * @return void
     */
    public function set($value);

    /**
     * Put a key / value pair or array of key / value pairs in the cart storage.
     *
     * @param  string|array  $key
     * @param  mixed  $value
     * @return void
     */
    // public function put($key, $value = null);

    /**
     * Remove an item from the cart storage, returning its value.
     *
     * @param  string  $key
     * @return mixed
     */
    public function remove($key);

    /**
     * Remove one or many items from the cart storage.
     *
     * @param  string|array  $keys
     * @return void
     */
    // public function forget($keys);

    /**
     * Push a value onto an array cart storage value.
     *
     * @param  mixed $value
     * @return void
     */
    public function push($value);

    /**
     * Remove all of the items from the cart storage.
     *
     * @return void
     */
    public function flush();
}
