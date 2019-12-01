<?php

namespace Jenky\Cartolic\Contracts;

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
     * Prepend a value onto an array cart storage value.
     *
     * @param  mixed $value
     * @return void
     */
    public function prepend($value);

    /**
     * Push a value onto an array cart storage value.
     *
     * @param  mixed $value
     * @return void
     */
    public function push($value);
}
