<?php

namespace Jenky\Cartolic;

use Brick\Money\AbstractMoney;
use Brick\Money\Money as BrickMoney;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Traits\ForwardsCalls;
use Jenky\Cartolic\Contracts\Money as Contract;

class Money implements Contract, Arrayable, Jsonable, Renderable
{
    use ForwardsCalls;

    /**
     * The Brick Money instance.
     *
     * @var \Brick\Money\AbstractMoney
     */
    protected $money;

    /**
     * Create new money instance.
     *
     * @param  \Brick\Money\AbstractMoney $money
     * @return void
     */
    public function __construct(AbstractMoney $money)
    {
        $this->money = $money;
    }

    /**
     * Create money with zero value.
     *
     * @return self
     */
    public static function zero(?string $currency = null)
    {
        return new static(BrickMoney::zero($currency ?: config('cart.currency')));
    }

    /**
     * Get the underlying Brick Money.
     *
     * @return self
     */
    public function toMoney(): AbstractMoney
    {
        return $this->money;
    }

    /**
     * Get the amount of the money.
     *
     * @return \Brick\Math\BigNumber
     */
    public function amount()
    {
        return $this->money->getAmount();
    }

    /**
     * Get the currency of the money.
     *
     * @return \Brick\Money\Currency
     */
    public function currency()
    {
        return $this->money->getCurrency();
    }

    public function format(?\NumberFormatter $formatter = null): string
    {
        return $formatter ? $this->money->formatWith($formatter) : $this->money->formatTo('en_US');
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'amount' => (string) $this->amount(),
            'currency' => (string) $this->currency(),
            'formatted' => $this->format(),
        ];
    }

    /**
     * Convert the purchasable into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the purchasable instance to JSON.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Get the evaluated contents of the money.
     *
     * @return string
     */
    public function render()
    {
        return $this->format();
    }

    /**
     * Dynamically call the money instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (isset($parameters[0]) && $parameters[0] instanceof Contract) {
            $parameters[0] = $parameters[0]->toMoney();
        }

        $result = $this->forwardCallTo($this->money, $method, $parameters);

        return $result instanceof AbstractMoney ? new static($result) : $result;
    }
}
