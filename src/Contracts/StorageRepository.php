<?php

namespace Jenky\Cartolic\Contracts;

use Illuminate\Support\Collection;

interface StorageRepository
{
    /**
     * Determine if the given cart storage value exists.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * Get the specified cart storage value.
     *
     * @param  array|string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Get all of the cart storage items for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection;

    /**
     * Set a given cart storage value.
     *
     * @param  array|string $key
     * @param  mixed $value
     * @return void
     */
    public function set($key, $value = null);

    /**
     * Prepend a value onto an array cart storage value.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function prepend($key, $value);

    /**
     * Push a value onto an array cart storage value.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function push($key, $value);
}
