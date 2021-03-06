<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Statement;

use Foundry\Builder\Clause\Insert\Columns;
use Foundry\Builder\Clause\Insert\Ignore;
use Foundry\Builder\Clause\Insert\Table;
use Foundry\Builder\Clause\Insert\Values;
use Foundry\Builder\StatementBuilder;

/**
 * INSERT builder.
 */
class Insert extends StatementBuilder
{
    const PART_IGNORE = 'ignore';
    const PART_TABLE = 'table';
    const PART_COLUMNS = 'columns';
    const PART_VALUES = 'values';

    /**
     * Set the IGNORE clause.
     *
     * @param bool $value
     * @return $this
     */
    public function ignore(bool $value = true)
    {
        /** @var Ignore $part */
        $part = $this->getPart('ignore');
        $part->setValue($value);
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
        /** @var Table $part */
        $part = $this->getPart('table');
        $part->setTable($table);
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
        /** @var Columns $part */
        $part = $this->getPart('columns');
        $part->addColumns($columns);
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
        /** @var Values $part */
        $part = $this->getPart('values');
        $part->addValues($values);
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        $this->statementName = 'INSERT';

        $this->parts = [
            self::PART_IGNORE => 'insert/ignore',
            self::PART_TABLE => 'insert/table',
            self::PART_COLUMNS => 'insert/columns',
            self::PART_VALUES => 'insert/values',
        ];
    }
}
