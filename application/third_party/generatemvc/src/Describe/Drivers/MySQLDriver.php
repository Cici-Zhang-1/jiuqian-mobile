<?php

namespace Generatemvc\Describe\Drivers;

use PDO;
use Generatemvc\Describe\Column;

/**
 * MySQL Driver
 *
 * A database driver extension for MySQL.
 * 
 * @package  Describe
 * @category Drivers
 * @author   Generatemvc Royce Gutib <rougingutib@gmail.com>
 */
class MySQLDriver implements DriverInterface
{
    protected $database;
    protected $columns = [];
    protected $pdo;

    /**
     * @param PDO    $pdo
     * @param string $database
     */
    public function __construct(PDO $pdo, $database)
    {
        $this->database = $database;
        $this->pdo = $pdo;
    }

    /**
     * Returns the result.
     * 
     * @return array
     */
    public function getTable($table)
    {
        $this->columns = [];

        $information = $this->pdo->prepare('DESCRIBE ' . $table);
        $information->execute();
        $information->setFetchMode(PDO::FETCH_OBJ);

        if ($stripped = strpos($table, '.')) {
            $table = substr($table, $stripped + 1);
        }

        while ($row = $information->fetch()) {
            preg_match('/(.*?)\((.*?)\)/', $row->Type, $match);

            $column = new Column;

            if ($row->Extra == 'auto_increment') {
                $column->setAutoIncrement(TRUE);
            }

            if ($row->Null == 'YES') {
                $column->setNull(TRUE);
            }

            switch ($row->Key) {
                case 'PRI':
                    $column->setPrimary(TRUE);

                    break;
                
                case 'MUL':
                    $column->setForeign(TRUE);

                    break;

                case 'UNI':
                    $column->setUnique(TRUE);

                    break;
            }

            $column->setDataType($row->Type);
            $column->setDefaultValue($row->Default);
            $column->setField($row->Field);

            if (isset($match[1])) {
                $column->setDataType($match[1]);
                $column->setLength($match[2]);
            }

            $query = 'SELECT COLUMN_NAME as "column",' .
                'REFERENCED_COLUMN_NAME as "referenced_column",' .
                'CONCAT(' .
                    'REFERENCED_TABLE_SCHEMA, ".",' .
                    'REFERENCED_TABLE_NAME' .
                ') as "referenced_table"' .
                'FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE ' .
                'WHERE CONSTRAINT_SCHEMA = "' . $this->database . '" ' .
                'AND TABLE_NAME = "' . $table . '";';

            $foreignTable = $this->pdo->prepare($query);
            $foreignTable->execute();
            $foreignTable->setFetchMode(PDO::FETCH_OBJ);

            while ($foreignRow = $foreignTable->fetch()) {
                if ($foreignRow->column == $row->Field) {
                    $column->setReferencedField($foreignRow->referenced_column);
                    $column->setReferencedTable($foreignRow->referenced_table);
                }
            }

            array_push($this->columns, $column);
        }

        return $this->columns;
    }

    /**
     * Shows the list of tables.
     * 
     * @return array
     */
    public function showTables()
    {
        $tables = [];

        $information = $this->pdo->prepare('SHOW TABLES');
        $information->execute();

        while ($row = $information->fetch()) {
            array_push($tables, $row[0]);
        }

        return $tables;
    }
}
