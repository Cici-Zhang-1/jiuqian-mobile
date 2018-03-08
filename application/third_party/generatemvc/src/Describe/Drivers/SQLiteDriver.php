<?php

namespace Generatemvc\Describe\Drivers;

use PDO;
use Generatemvc\Describe\Column;

/**
 * SQLite Driver
 *
 * A database driver extension for SQLite.
 * 
 * @package  Describe
 * @category Drivers
 * @author   Generatemvc Royce Gutib <rougingutib@gmail.com>
 */
class SQLiteDriver implements DriverInterface
{
    protected $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Returns the result.
     * 
     * @return array
     */
    public function getTable($table)
    {
        $columns = [];

        // Gets list of columns
        $query = 'PRAGMA table_info("' . $table . '");';
        $information = $this->pdo->prepare($query);

        $information->execute();
        $information->setFetchMode(PDO::FETCH_OBJ);

        while ($row = $information->fetch()) {
            $column = new Column;

            if ( ! $row->notnull) {
                $column->setNull(true);
            }

            if ($row->pk) {
                $column->setPrimary(true);
                $column->setAutoIncrement(true);
            }

            $column->setDefaultValue($row->dflt_value);
            $column->setField($row->name);
            $column->setDataType(strtolower($row->type));

            array_push($columns, $column);
        }

        // Gets list of foreign keys
        $query = 'PRAGMA foreign_key_list("' . $table . '");';
        $information = $this->pdo->prepare($query);

        $information->execute();
        $information->setFetchMode(PDO::FETCH_OBJ);

        while ($row = $information->fetch()) {
            foreach ($columns as $column) {
                if ($column->getField() == $row->from) {
                    $column->setForeign(true);

                    $column->setReferencedField($row->to);
                    $column->setReferencedTable($row->table);
                }
            }
        }

        return $columns;
    }

    /**
     * Shows the list of tables.
     * 
     * @return array
     */
    public function showTables()
    {
        $tables = [];

        // Gets list of columns
        $query = 'SELECT name FROM sqlite_master WHERE type = "table";';
        $information = $this->pdo->prepare($query);

        $information->execute();
        $information->setFetchMode(PDO::FETCH_OBJ);

        while ($row = $information->fetch()) {
            if ($row->name != 'sqlite_sequence') {
                array_push($tables, $row->name);
            }
        }

        return $tables;
    }
}
