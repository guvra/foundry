<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Statement;

use Foundry\Builder\Clause\Update\Limit;
use Foundry\Builder\Clause\Update\Table;
use Foundry\Builder\Clause\Update\Values;
use Foundry\Builder\StatementBuilder;
use Foundry\Builder\Traits\HasJoin;
use Foundry\Builder\Traits\HasWhere;

/**
 * UPDATE builder.
 */
class Update extends StatementBuilder
{
    const PART_TABLE = 'table';
    const PART_JOIN = 'join';
    const PART_VALUES = 'values';
    const PART_WHERE = 'where';
    const PART_LIMIT = 'limit';

    use HasJoin;
    use HasWhere;

    /**
     * Set the table to update.
     *
     * @param string $table
     * @param string $alias
     * @return $this
     */
    public function table(string $table, string $alias = '')
    {
        /** @var Table $part */
        $part = $this->getPart('table');
        $part->setTable($table, $alias);
        $this->compiled = null;

        return $this;
    }

    /**
     * Set the values to update.
     *
     * @param array $values
     * @return $this
     */
    public function values(array $values)
    {
        /** @var Values $part */
        $part = $this->getPart('values');
        $part->setValues($values);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a limit clause to the query.
     *
     * @param int $max
     * @return $this
     */
    public function limit(int $max)
    {
        /** @var Limit $part */
        $part = $this->getPart('limit');
        $part->setLimit($max);
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        $this->statementName = 'UPDATE';

        $this->parts = [
            self::PART_TABLE => 'update/table',
            self::PART_JOIN => 'update/join',
            self::PART_VALUES => 'update/values',
            self::PART_WHERE => 'update/where',
            self::PART_LIMIT => 'update/limit',
        ];
    }
}
