<?php

namespace Generatemvc\Describe;

use Generatemvc\Describe\Drivers\DriverInterface;

/**
 * Describe
 *
 * Gets information of a table schema from a database.
 * 
 * @package Describe
 */
class Describe
{
    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var \Generatemvc\Describe\Driver\DriverInterface
     */
    protected $driver;

    /**
     * @param \Generatemvc\Describe\Driver\DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Gets the primary key in the specified table.
     * 
     * @param  string $table
     * @return string
     */
    public function getPrimaryKey($table)
    {
        $result = '';

        if (empty($this->columns)) {
            $this->columns = $this->driver->getTable($table);
        }

        foreach ($this->columns as $column) {
            if ($column->isPrimaryKey()) {
                $result = $column->get_field();
            }
        }

        return $result;
    }

    /**
     * Returns the result.
     *
     * @param  string $table
     * @return array
     */
    public function getTable($table)
    {
        return $this->driver->getTable($table);
    }

    /**
     * Returns the result.
     * 
     * @param  string $table
     * @return array
     */
    public function get_table($table)
    {
        return $this->driver->getTable($table);
    }

    /**
     * Gets the primary key in the specified table.
     * 
     * @param  string $table
     * @return string
     */
    public function get_primary_key($table)
    {
        return $this->getPrimaryKey($table);
    }

    /**
     * Shows the list of tables.
     * 
     * @return array
     */
    public function showTables()
    {
        return $this->driver->showTables();
    }

    /**
     * Shows the list of tables.
     * 
     * @return array
     */
    public function show_tables()
    {
        return $this->driver->showTables();
    }
}
