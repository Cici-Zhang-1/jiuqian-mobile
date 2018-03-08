<?php

namespace Generatemvc\Describe\Drivers;

use PDO;

/**
 * CodeIgniter Driver
 *
 * A database driver specifically used for CodeIgniter.
 * 
 * @package  Describe
 * @category Drivers
 */
class CodeIgniterDriver implements DriverInterface
{
    /**
     * @var string
     */
    protected $connection;

    /**
     * @var array
     */
    protected $database;

    /**
     * @param array  $database
     * @param string $connection
     */
    public function __construct(array $database, $connection = 'default')
    {
        $this->database = $database;
        $this->connection = $connection;
    }

    /**
     * Gets the specified driver from the specified database connection.
     * 
     * @param  array  $database
     * @param  string $connection
     * @return \Generatemvc\Describe\Driver\DriverInterface|null
     */
    public function getDriver(array $database, $connection)
    {
        $driver = null;

        switch ($database[$connection]['dbdriver']) {
            case 'mysql':
            case 'mysqli':
                $mysql = $database[$connection];

                $pdo = new PDO(
                    'mysql:host=' . $mysql['hostname'] .
                    ';dbname=' . $mysql['database'],
                    $mysql['username'],
                    $mysql['password']
                );

                $driver = new MySQLDriver($pdo, $mysql['database']);

                break;
            case 'pdo':
            case 'sqlite':
            case 'sqlite3':
                $pdo = new PDO($database[$connection]['hostname']);

                $driver = new SQLiteDriver($pdo);

                break;
        }

        return $driver;
    }

    /**
     * Returns the result.
     * 
     * @return array
     */
    public function getTable($table)
    {
        $driver = $this->getDriver($this->database, $this->connection);

        return $driver->getTable($table);
    }

    /**
     * Shows the list of tables.
     * 
     * @return array
     */
    public function showTables()
    {
        $driver = $this->getDriver($this->database, $this->connection);

        return $driver->showTables();
    }
}
