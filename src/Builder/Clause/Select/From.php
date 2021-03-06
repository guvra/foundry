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
class From extends Builder
{
    /**
     * @var string[]
     */
    protected $tables = [];

    /**
     * Add tables.
     *
     * @param array $tables
     * @return $this
     */
    public function addTables(array $tables)
    {
        $this->tables = array_merge($this->tables, $tables);
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        if (empty($this->tables)) {
            return '';
        }

        $parts = [];

        foreach ($this->tables as $alias => $table) {
            $parts[] = is_string($alias) && $alias !== '' ? "$table AS $alias" : "$table";
        }

        return 'FROM ' . implode(', ', $parts);
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        unset($this->tables);
        $this->tables = [];

        return $this;
    }
}
