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
     * The database table name that should be used.
     *
     * @var string
     */
    protected $table;

    /**
     * Create new Database repository instance.
     *
     * @param  string $connection
     * @param  string $table
     * @return void
     */
    public function __construct(string $connection, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }
}
