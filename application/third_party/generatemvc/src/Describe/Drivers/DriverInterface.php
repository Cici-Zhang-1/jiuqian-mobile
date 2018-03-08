<?php

namespace Generatemvc\Describe\Drivers;

/**
 * Database Driver Interface
 *
 * An interface for handling PDO drivers.
 * 
 * @package  Describe
 * @category Drivers
 */
interface DriverInterface
{
    /**
     * Returns the result.
     * 
     * @return array
     */
    public function getTable($table);

    /**
     * Shows the list of tables.
     * 
     * @return array
     */
    public function showTables();
}
