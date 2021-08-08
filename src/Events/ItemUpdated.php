<?php

namespace Jenky\Cartolic\Events;

use Jenky\Cartolic\Item;

class ItemUpdated
{
    /**
     * The updated cart item.
     *
     * @var \Jenky\Cartolic\Item
     */
    public $item;

    /**
     * Create new event instance.
     *
     * @param  \Jenky\Cartolic\Item  $item
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}
