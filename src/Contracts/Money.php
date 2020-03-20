<?php

namespace Jenky\Cartolic\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use JsonSerializable;

interface Money extends Arrayable, Jsonable, JsonSerializable, Renderable
{
    public function amount();

    public function currency();

    public function format(\NumberFormatter $formatter): string;
}
