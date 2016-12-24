<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Data;

use Guvra\Builder\QueryableBuilder;

/**
 * Insert builder.
 */
class Insert extends QueryableBuilder
{
    /**
     * Whether to ignore conflicts with existing rows.
     *
     * @var bool
     */
    protected $ignore = false;

    /**
     * The target table.
     *
     * @var string
     */
    protected $table = '';

    /**
     * Columns to look up.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Values to insert.
     *
     * @var array
     */
    protected $values = [];

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $this->compiled = 'INSERT'
            . $this->buildIgnore($this->ignore)
            . $this->buildTable($this->table, 'INTO')
            . $this->buildColumns($this->columns, true, true)
            . $this->buildValues($this->values);

        return $this->compiled;
    }

    /**
     * Build the ignore clause.
     *
     * @param bool $value
     * @return string
     */
    protected function buildIgnore($value)
    {
        return $value ? ' IGNORE' : '';
    }

    /**
     * Build the values clause.
     *
     * @param array $values
     * @return string
     */
    protected function buildValues(array $values)
    {
        foreach ($values as $key => $value) {
            if (is_string($value)) {
                $values[$key] = $this->connection->quote($value);
            }
        }

        return ' VALUES (' . implode(', ', $values) . ')';
    }

    /**
     * Build the ignore clause.
     *
     * @param bool $value
     * @return $this
     */
    public function ignore($value = true)
    {
        $this->compiled = false;
        $this->ignore = (bool) $value;

        return $this;
    }

    /**
     * Set the INTO clause.
     *
     * @param string $table
     * @return $this
     */
    public function into($table)
    {
        $this->compiled = false;
        $this->table = (string) $table;

        return $this;
    }

    /**
     * Set the columns to insert.
     *
     * @param array $columns
     * @return $this
     */
    public function columns(array $columns)
    {
        $this->compiled = false;
        $this->columns = $columns;

        return $this;
    }

    /**
     * Set the values to insert.
     *
     * @param array $values
     * @return $this
     */
    public function values(array $values)
    {
        $this->compiled = false;
        $this->values = $values;

        return $this;
    }
}
