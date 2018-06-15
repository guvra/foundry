<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause\Update;

use Guvra\Builder\Builder;

/**
 * Table builder.
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

        return $this->alias !== '' ? $this->table . ' AS ' . $this->alias : $this->table;
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
