<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Statement;

use Foundry\Builder\Clause\Delete\Limit;
use Foundry\Builder\Clause\Delete\Table;
use Foundry\Builder\StatementBuilder;
use Foundry\Builder\Traits\HasJoin;
use Foundry\Builder\Traits\HasWhere;

/**
 * DELETE builder.
 */
class Delete extends StatementBuilder
{
    const PART_TABLE = 'table';
    const PART_JOIN = 'join';
    const PART_WHERE = 'where';
    const PART_LIMIT = 'limit';

    use HasJoin;
    use HasWhere;

    /**
     * Set the FROM clause.
     *
     * @param string $table
     * @param string $alias
     * @return $this
     */
    public function from(string $table, string $alias = '')
    {
        /** @var Table $part */
        $part = $this->getPart('table');
        $part->setTable($table, $alias);
        $this->compiled = null;

        return $this;
    }

    /**
     * SET the LIMIT clause.
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
        $this->statementName = 'DELETE';

        $this->parts = [
            self::PART_TABLE => 'delete/table',
            self::PART_JOIN => 'delete/join',
            self::PART_WHERE => 'delete/where',
            self::PART_LIMIT => 'delete/limit',
        ];
    }
}
