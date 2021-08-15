<?php

namespace Jenky\Cartolic\Tests\Fixtures;

use Faker\Generator;
use Illuminate\Support\Str;
use Jenky\Cartolic\Contracts\Purchasable;

class Product implements Purchasable
{
    public $sku;

    public $name;

    public $description;

    public $price;

    public function __construct(Generator $faker)
    {
        $this->sku = Str::uuid();
        $this->name = $faker->catchPhrase();
        $this->description = $faker->words(4, true);
        $this->price = $faker->numberBetween(1, 100);
    }

    public function sku()
    {
        return (string) $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function price()
    {
        return $this->price;
    }

    public function options(): array
    {
        return [];
    }

    public function metadata(): array
    {
        return [];
    }
}
