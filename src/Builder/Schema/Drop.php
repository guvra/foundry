<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Schema;

use Guvra\Builder\QueryableBuilder;

/**
 * Table drop builder.
 */
class Drop extends QueryableBuilder
{
    /**
     * @var bool
     */
    protected $exists = false;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @param bool $value
     * @return $this
     */
    public function exists(bool $value)
    {
        $this->exists = $value;
        $this->compiled = null;

        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function table(string $table)
    {
        $this->table = $table;
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        if ($this->table === '') {
            throw new \UnexpectedValueException('The table name is required.');
        }

        return 'DROP'
            . $this->buildExists()
            . $this->buildTable();
    }

    /**
     * @return string
     */
    protected function buildExists()
    {
        return $this->exists ? ' IF EXISTS' : '';
    }

    /**
     * @return string
     */
    protected function buildTable()
    {
        return ' ' . $this->table;
    }
}
