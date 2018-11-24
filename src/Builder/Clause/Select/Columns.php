<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Clause\Select;

use Foundry\Builder\Builder;

/**
 * FROM builder.
 */
class Columns extends Builder
{
    /**
     * @var array
     */
    protected $columns = [];

    /**
     * Set the columns.
     *
     * @param array $columns
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
        $this->compiled = null;

        return $this;
    }

    /**
     * Add columns.
     *
     * @param array $columns
     * @return $this
     */
    public function addColumns(array $columns)
    {
        $this->columns = array_merge($this->columns, $columns);
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        if (empty($this->columns)) {
            return '*';
        }

        $parts = [];

        foreach ($this->columns as $alias => $column) {
            $column = $this->parseSubQuery($column);
            $parts[] = is_string($alias) && $alias !== '' ? "$column AS $alias" : "$column";
        }

        return implode(', ', $parts);
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        unset($this->columns);
        $this->columns = [];

        return $this;
    }
}
