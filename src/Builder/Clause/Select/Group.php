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
 * GROUP BY builder.
 */
class Group extends Builder
{
    /**
     * @var string[]
     */
    protected $columns = [];

    /**
     * Add columns.
     *
     * @param string[] $columns
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
            return '';
        }

        return 'GROUP BY ' . implode(', ', $this->columns);
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
