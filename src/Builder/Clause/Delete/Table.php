<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause\Delete;

use Guvra\Builder\Builder;

/**
 * FROM builder.
 */
class Table extends Builder
{
    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var string
     */
    protected $alias = '';

    /**
     * Set the table.
     *
     * @param string $table
     * @param string $alias
     * @return $this
     */
    public function setTable(string $table, string $alias = '')
    {
        $this->table = $table;
        $this->alias = $alias;
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        if ($this->table === '') {
            return '';
        }

        $result = 'FROM ' . $this->table;

        return $this->alias !== '' ? $result . ' AS ' . $this->alias : $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        $this->table = '';
        $this->alias = '';

        return $this;
    }
}
