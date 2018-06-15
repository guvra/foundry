<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Statement;

use Foundry\Builder\Clause\Select\Columns;
use Foundry\Builder\Clause\Select\Distinct;
use Foundry\Builder\Clause\Select\From;
use Foundry\Builder\Clause\Select\Group;
use Foundry\Builder\Clause\Select\Limit;
use Foundry\Builder\Clause\Select\Order;
use Foundry\Builder\Clause\Select\Union;
use Foundry\Builder\StatementBuilder;
use Foundry\Builder\Traits\HasHaving;
use Foundry\Builder\Traits\HasJoin;
use Foundry\Builder\Traits\HasWhere;

/**
 * SELECT builder.
 */
class Select extends StatementBuilder
{
    const PART_COLUMNS = 'columns';
    const PART_DISTINCT = 'distinct';
    const PART_FROM = 'from';
    const PART_JOIN = 'join';
    const PART_WHERE = 'where';
    const PART_GROUP = 'group';
    const PART_HAVING = 'having';
    const PART_ORDER = 'order';
    const PART_LIMIT = 'limit';
    const PART_UNION = 'union';

    use HasJoin;
    use HasWhere;
    use HasHaving;

    /**
     * Set the columns to select.
     *
     * @param string|array $columns
     * @return $this
     */
    public function columns($columns)
    {
        if (!is_array($columns)) {
            $columns = [$columns];
        }

        /** @var Columns $part */
        $part = $this->getPart('columns');
        $part->setColumns($columns);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add/remove a distinct clause to the query.
     *
     * @param bool $value
     * @return $this
     */
    public function distinct(bool $value = true)
    {
        /** @var Distinct $part */
        $part = $this->getPart('distinct');
        $part->setValue($value);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a from clause to the query.
     *
     * @param string|array $tables
     * @return $this
     */
    public function from($tables)
    {
        if (!is_array($tables)) {
            $tables = [$tables];
        }

        /** @var From $part */
        $part = $this->getPart('from');
        $part->setTables($tables);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a group by clause to the query.
     *
     * @param string|array $columns
     * @return $this
     */
    public function group($columns)
    {
        if (!is_array($columns)) {
            $columns = [$columns];
        }

        /** @var Group $part */
        $part = $this->getPart('group');
        $part->setColumns($columns);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add an order by clause to the query.
     *
     * @param string|array $orders
     * @return $this
     */
    public function order($orders)
    {
        if (!is_array($orders)) {
            $orders = [$orders];
        }

        /** @var Order $part */
        $part = $this->getPart('order');
        $part->setOrders($orders);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a limit clause to the query.
     *
     * @param int $max
     * @param int $start
     * @return $this
     */
    public function limit(int $max, int $start = 0)
    {
        /** @var Limit $part */
        $part = $this->getPart('limit');
        $part->setLimit($max);
        $part->setOffset($start);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a union clause to the query.
     *
     * @param mixed $query
     * @param bool $all
     * @return $this
     */
    public function union($query, $all = false)
    {
        /** @var Union $part */
        $part = $this->getPart('union');
        $part->addUnion($query, $all);
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        $this->statementName = 'SELECT';

        $this->parts = [
            self::PART_DISTINCT => 'select/distinct',
            self::PART_COLUMNS => 'select/columns',
            self::PART_FROM => 'select/from',
            self::PART_JOIN => 'select/join',
            self::PART_WHERE => 'select/where',
            self::PART_GROUP => 'select/group',
            self::PART_HAVING => 'select/having',
            self::PART_ORDER => 'select/order',
            self::PART_LIMIT => 'select/limit',
            self::PART_UNION => 'select/union',
        ];
    }
}
