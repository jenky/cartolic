<?php

namespace Jenky\Cartolic\Contracts\Fee;

interface Collector
{
    /**
     * Get all the fees.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Add a new fee.
     *
     * @param  \Jenky\Cartolic\Contracts\Fee\Fee $fee
     * @return mixed
     */
    public function add(Fee $fee);

    /**
     * Get a fee.
     *
     * @param  string|int $fee
     * @return \Jenky\Cartolic\Contracts\Fee\Fee|null
     */
    public function get($fee): ?Fee;

    /**
     * Get total amounts of all fees.
     *
     * @return mixed
     */
    public function total();
}
