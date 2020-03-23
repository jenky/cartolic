<?php

namespace Jenky\Cartolic;

use Brick\Money\AbstractMoney;
use Brick\Money\Money as BrickMoney;
use Illuminate\Support\Traits\ForwardsCalls;
use Jenky\Cartolic\Contracts\Money as Contract;

class Money implements Contract
{
    use ForwardsCalls;

    protected $money;

    public function __construct(AbstractMoney $money)
    {
        $this->money = $money;
    }

    public static function zero(?string $currency = null)
    {
        return new static(BrickMoney::zero($currency ?: config('cart.currency')));
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
        if (isset($parameters[0]) && $parameters[0] instanceof Contract) {
            $parameters[0] = $parameters[0]->toMoney();
        }

        return new static(
            $this->forwardCallTo($this->money, $method, $parameters)
        );
    }
}
