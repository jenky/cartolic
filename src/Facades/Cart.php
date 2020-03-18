<?php

namespace Jenky\Cartolic\Facades;

use Illuminate\Support\Facades\Facade;
use Jenky\Cartolic\Contracts\Cart as Contract;

class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Contract::class;
    }
}
