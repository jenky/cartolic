<?php

namespace Jenky\Cartolic\Contracts\Fee;

interface Fee
{
    /**
     * Get the fee unique ID.
     *
     * @return string
     */
    public function id();

    /**
     * Get the fee name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Get the fee amount.
     *
     * @return mixed
     */
    public function amount();
}
