<?php

namespace Jenky\Cartolic\Storage;

use Illuminate\Contracts\Auth\Factory;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Jenky\Cartolic\Contracts\StorageRepository;

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
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create new Database repository instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @param  string  $table
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(ConnectionInterface $connection, string $table, Factory $auth)
    {
        $this->connection = $connection;
        $this->table = $table;
        $this->auth = $auth;
    }

    /**
     * Get a fresh query builder instance for the table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query()
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
        return $this->query()
            ->where('user_id', $this->auth->id())
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
            return $this->query()->insert(Arr::set($data, 'user_id', $this->auth->id()));
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
        return $this->query()->where('user_id', $this->auth->id())
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
     * Store the given items.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @return void
     */
    public function store(Collection $value)
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
     * Remove all of the items from the cart storage.
     *
     * @return void
     */
    public function flush()
    {
        $this->query()->where('user_id', $this->auth->id())->delete();
    }
}
