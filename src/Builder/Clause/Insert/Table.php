<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause\Insert;

use Guvra\Builder\Builder;

/**
 * INTO builder.
 */
class Table extends Builder
{
    /**
     * @var string
     */
    protected $table = '';

    /**
     * Set the table.
     *
     * @param string $table
     * @return $this
     */
    public function setTable(string $table)
    {
        $this->table = $table;

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

        return 'INTO ' . $this->table;
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        $this->table = '';

        return $this;
    }
}
