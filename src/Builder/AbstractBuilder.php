<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

use Guvra\Builder\Condition\ConditionGroup;
use Guvra\ConnectionInterface;
use Guvra\Builder\Condition\ConditionFactory;

/**
 * Query builder.
 */
abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var bool|string
     */
    protected $compiled = false;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get the SQL query string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->compiled !== false ? $this->compiled : $this->build();
    }

    /**
     * Build a query string for the specified columns.
     *
     * @param array $columns
     * @param bool  $restrict
     * @param bool  $enclose
     * @return string
     */
    protected function buildColumns(array $columns, $restrict = false, $enclose = false)
    {
        $value = '';

        if (!empty($columns)) {
            $values = [];
            foreach ($columns as $alias => $column) {
                if (!$restrict && is_object($column) && $column instanceof Builder) {
                    $column = "({$column->build()})";
                }
                $values[] = (!$restrict && is_string($alias) && $alias !== '') ? "$column AS $alias" : "$column";
            }

            $value = implode(', ', $values);
            $value = ($enclose) ? " ($value)" : " $value";
        }

        return $value;
    }

    /**
     * Build any clause targeting a table.
     *
     * @param string $table
     * @param string $clause
     * @return string
     */
    protected function buildTable($table, $clause = '')
    {
        if ($clause) {
            $clause = " $clause";
        }

        return ($table !== '') ? "$clause $table" : '';
    }
}
