<?php

namespace Jenky\Cartolic;

use Brick\Money\AbstractMoney;
use Illuminate\Support\Traits\ForwardsCalls;

class Money implements Contracts\Money
{
    use ForwardsCalls;

    protected $money;

    public function __construct(AbstractMoney $money)
    {
        $this->money = $money;
    }

    public function toMoney(): AbstractMoney
    {
        return $this->money;
    }

    public function amount()
    {
        return $this->money->getAmount();
    }

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
     * @return self
     */
    public function __call($method, $parameters)
    {
        return new static(
            $this->forwardCallTo($this->money, $method, $parameters)
        );
    }
}
