<?php

namespace Jenky\Cartolic\Storage;

use Jenky\Cartolic\Contracts\Storage\StorageRepository;

class DatabaseRepository implements StorageRepository
{
    /**
     * The database connection name that should be used.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create new Database repository instance.
     *
     * @param  string $driver
     * @return void
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }
}
