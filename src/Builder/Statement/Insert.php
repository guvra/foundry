<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Statement;

use Guvra\Builder\Builder;

/**
 * Insert builder.
 */
class Insert extends Builder
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
     * @var string|null
     */
    protected $insertMode;

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

        $isMultipleInsert = null;
        $parts = [];

        foreach ($this->values as $value) {
            $isArray = is_array($value);

            // Check if we need to insert a single set of values, or multiple sets of values
            if ($isMultipleInsert === null) {
                $isMultipleInsert = $isArray;
            }

            // Escape values and prepare them for inclusion in a string value
            $parts[] = $isMultipleInsert ? $this->prepareValues($value) : $this->escapeValue($value);
        }

        return $isMultipleInsert
            ? ' VALUES ' . implode(',', $parts)
            : ' VALUES (' . implode(',', $parts) . ')';
    }

    /**
     * Convert the values to an inline string.
     *
     * @param array $values
     * @return string
     * @throws \LogicException
     */
    protected function prepareValues($values)
    {
        if (!is_array($values)) {
            throw new \LogicException('In multiple insertion mode, all values must be arrays.');
        }

        foreach ($values as $key => $value) {
            $values[$key] = $this->escapeValue($value);
        }

        return '(' . implode(',', $values) . ')';
    }

    /**
     * Escape the value.
     *
     * @param mixed $value
     * @return string
     */
    protected function escapeValue($value)
    {
        if (is_string($value)) {
            $value = $this->connection->quote($value);
        }

        return $value;
    }
}
