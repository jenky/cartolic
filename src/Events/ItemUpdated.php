<?php

namespace Jenky\Cartolic\Events;

use Jenky\Cartolic\Contracts\Cart\Item;

class ItemUpdated
{
    /**
     * The updated cart item.
     *
     * @var \Jenky\Cartolic\Contracts\Cart\Item
     */
    public $item;

    /**
     * Create new event instance.
     *
     * @param  \Jenky\Cartolic\Contracts\Cart\Item $item
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}
