<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
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
     * @var bool
     */
    protected $ignore = false;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $values = [];

    /**
     * Build the ignore clause.
     *
     * @param bool $value
     * @return $this
     */
    public function ignore(bool $value = true)
    {
        $this->ignore = $value;
        $this->compiled = null;

        return $this;
    }

    /**
     * Set the INTO clause.
     *
     * @param string $table
     * @return $this
     */
    public function into(string $table)
    {
        $this->table = $table;
        $this->compiled = null;

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
        $this->columns = $columns;
        $this->compiled = null;

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
        $this->values = $values;
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        return 'INSERT'
            . $this->buildIgnore()
            . $this->buildTable()
            . $this->buildColumns()
            . $this->buildValues();
    }

    /**
     * Build the ignore clause.
     *
     * @return string
     */
    protected function buildIgnore()
    {
        return $this->ignore ? ' IGNORE' : '';
    }

    /**
     * Build the table name.
     *
     * @return string
     */
    protected function buildTable()
    {
        if (empty($this->table)) {
            return '';
        }

        return " INTO {$this->table}";
    }

    /**
     * Build the columns.
     *
     * @return string
     */
    protected function buildColumns()
    {
        if (empty($this->columns)) {
            return '';
        }

        return ' (' . implode(', ', $this->columns) . ')';
    }

    /**
     * Build the values clause.
     *
     * @return string
     */
    protected function buildValues()
    {
        if (empty($this->values)) {
            return '';
        }

        $values = [];

        foreach ($this->values as $value) {
            if (is_string($value)) {
                $value = $this->connection->quote($value);
            }
            $values[] = $value;
        }

        return ' VALUES (' . implode(', ', $values) . ')';
    }
}
