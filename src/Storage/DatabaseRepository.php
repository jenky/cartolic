<?php

namespace Jenky\Cartolic\Storage;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Jenky\Cartolic\Contracts\Storage\StorageRepository;

class DatabaseRepository implements StorageRepository
{
    /**
     * The database connection that should be used.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * The database table name that should be used.
     *
     * @var string
     */
    protected $table;

    /**
     * The authentication guard that should be used.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $guard;

    /**
     * Create new Database repository instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface $connection
     * @param  string $table
     * @param  \Illuminate\Contracts\Auth\Guard $guard
     * @return void
     */
    public function __construct(ConnectionInterface $connection, string $table, Guard $guard)
    {
        $this->connection = $connection;
        $this->table = $table;
        $this->guard = $guard;
    }

    /**
     * Get a fresh query builder instance for the table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getQuery()
    {
        return $this->connection->table($this->table);
    }

    /**
     * Get the cart from database storage.
     *
     * @return object|null
     */
    protected function getCart()
    {
        return $this->getQuery()
            ->where('user_id', $this->guard->id())
            ->first();
    }

    /**
     * Get the cart items.
     *
     * @return array
     */
    protected function getCartItems(): array
    {
        $cart = $this->getCart();

        if (! $cart || empty($cart->items)) {
            return [];
        }

        return @unserialize(base64_decode($cart->items));
    }

    /**
     * Perform an insert operation on the cart.
     *
     * @param  array $data
     * @return bool|null
     */
    protected function performInsert(array $data)
    {
        try {
            return $this->getQuery()->insert(Arr::set($data, 'user_id', $this->guard->id()));
        } catch (QueryException $e) {
            $this->performUpdate($data);
        }
    }

    /**
     * Perform an update operation on the cart.
     *
     * @param  array $data
     * @return bool|null
     */
    protected function performUpdate(array $data)
    {
        return $this->getQuery()->where('user_id', $this->guard->id())
            ->update($data);
    }

    /**
     * Prepare data for the cart.
     *
     * @param  array $items
     * @return array
     */
    protected function prepareData(array $items): array
    {
        return [
            'items' => base64_encode(@serialize($items)),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Get all of the cart storage items for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        return Collection::make($this->getCartItems());
    }

    /**
     * Set a given cart storage value.
     *
     * @param  mixed $value
     * @return void
     */
    public function set($value)
    {
        $items = $this->getCartItems();
        $exists = ! empty($items);

        foreach ((array) $value as $arrayKey => $arrayValue) {
            Arr::set($items, $arrayKey, $arrayValue);
        }

        $data = $this->prepareData($items);

        return $exists ? $this->performUpdate($data) : $this->performInsert($data);
    }

    /**
     * Remove a given cart storage value.
     *
     * @param  mixed $value
     * @return mixed
     */
    public function remove($value)
    {
        $items = $this->getCartItems();

        Arr::forget($items, $value);

        return $this->performUpdate($this->prepareData($items));
    }

    /**
     * Push a value onto an array cart storage value.
     *
     * @param  mixed $value
     * @return void
     */
    public function push($value)
    {
        //
    }

    /**
     * Remove all of the items from the cart storage.
     *
     * @return void
     */
    public function flush()
    {
        $this->getQuery()->where('user_id', $this->guard->id())->delete();
    }
}
