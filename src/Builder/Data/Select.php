<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Data;

use Guvra\Builder\BuilderFactoryInterface;
use Guvra\Builder\QueryableBuilder;
use Guvra\ConnectionInterface;

/**
 * Select builder.
 */
class Select extends QueryableBuilder
{
    const PART_COLUMNS = 1;
    const PART_DISTINCT = 2;
    const PART_FROM = 4;
    const PART_JOIN = 8;
    const PART_WHERE = 16;
    const PART_GROUP = 32;
    const PART_HAVING = 64;
    const PART_ORDER = 128;
    const PART_LIMIT = 256;
    const PART_UNION = 512;

    use \Guvra\Builder\Traits\WhereTrait;
    use \Guvra\Builder\Traits\HavingTrait;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var bool
     */
    protected $distinct = false;

    /**
     * @var array
     */
    protected $tables = [];

    /**
     * @var \Guvra\Builder\Clause\Join[]
     */
    protected $joins = [];

    /**
     * @var \Guvra\Builder\Clause\Where
     */
    //protected $wheres;

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var \Guvra\Builder\Clause\Having
     */
    //protected $havings;

    /**
     * @var \Guvra\Builder\Clause\Order
     */
    protected $orders = [];

    /**
     * @var \Guvra\Builder\Clause\Limit
     */
    protected $limit = [];

    /**
     * @var array
     */
    protected $unions = [];

    /**
     * @param ConnectionInterface $connection
     * @param BuilderFactoryInterface|null $builderFactory
     */
    public function __construct(ConnectionInterface $connection, BuilderFactoryInterface $builderFactory = null)
    {
        parent::__construct($connection, $builderFactory);

        $this->groups = $builderFactory->create('group');
        $this->limit = $builderFactory->create('limit');
        $this->orders = $builderFactory->create('order');
    }

    /**
     * Set the columns to select.
     *
     * @param array $columns
     * @return $this
     */
    public function columns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Add/remove a distinct clause to the query.
     *
     * @param bool $value
     * @return $this
     */
    public function distinct($value = true)
    {
        $this->distinct = (bool) $value;

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
            $tables = (array) $tables;
        }

        $this->tables = $tables;

        return $this;
    }

    /**
     * Add a join clause to the query.
     *
     * @param string|array $table
     * @param array        $conditions
     * @param string       $type
     * @return $this
     */
    public function join($table, ...$args)
    {
        $join = $this->builderFactory->create('join', $this->builderFactory);
        $this->joins[] = $join->join('inner', $table, ...$args);

        return $this;
    }

    /**
     * Add a left join clause to the query.
     *
     * @param string|array $tables
     * @return $this
     */
    public function joinLeft($table, ...$args)
    {
        $join = $this->builderFactory->create('join', $this->builderFactory);
        $this->joins[] = $join->join('left', $table, ...$args);

        return $this;
    }

    /**
     * Add a right join clause to the query.
     *
     * @param string|array  $tables
     * @param array        $conditions
     * @return $this
     */
    public function joinRight($table, ...$args)
    {
        $join = $this->builderFactory->create('join', $this->builderFactory);
        $this->joins[] = $join->join('right', $table, ...$args);

        return $this;
    }

    /**
     * Add a left join clause to the query.
     *
     * @param string|array  $tables
     * @param array        $conditions
     * @return $this
     */
    public function joinCross($table)
    {
        $join = $this->builderFactory->create('join', $this->builderFactory);
        $this->joins[] = $join->join('cross', $table);

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
            $this->groups->addColumn($columns);
        } else {
            $this->groups->addColumns($columns);
        }

        return $this;
    }

    /**
     * Add an order by clause to the query.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function order($column, $direction = 'ASC')
    {
        $this->orders->addOrder($column, $direction);

        return $this;
    }

    /**
     * Add a limit clause to the query.
     *
     * @param int $max
     * @param int $start
     * @return $this
     */
    public function limit($max, $start = 0)
    {
        $this->limit->setMax($max)
            ->setStart($start);

        return $this;
    }

    /**
     * Add a union clause to the query.
     *
     * @param Select $query
     * @param bool   $all
     * @return $this
     */
    public function union(Select $query, $all = false)
    {
        $this->unions[] = compact('query', 'all');

        return $this;
    }

    /**
     * Reset a part of the query, (or the whole query).
     *
     * @param int|null $part
     * @return $this
     */
    public function reset($part = null)
    {
        if (!$part || $part & self::PART_COLUMNS) {
            $this->columns = [];
        }

        if (!$part || $part & self::PART_DISTINCT) {
            $this->distinct = false;
        }

        if (!$part || $part & self::PART_FROM) {
            $this->tables = [];
        }

        if (!$part || $part & self::PART_JOIN) {
            $this->joins = $this->builderFactory->create('join', $this->builderFactory);
        }

        if (!$part || $part & self::PART_WHERE) {
            $this->wheres = $this->builderFactory->create('where', $this->builderFactory);
        }

        if (!$part || $part & self::PART_GROUP) {
            $this->groups = $this->builderFactory->create('group');
        }

        if (!$part || $part & self::PART_HAVING) {
            $this->havings = $this->builderFactory->create('having', $this->builderFactory);
        }

        if (!$part || $part & self::PART_ORDER) {
            $this->orders = $this->builderFactory->create('order');
        }

        if (!$part || $part & self::PART_LIMIT) {
            $this->limit = $this->builderFactory->create('limit');
        }

        if (!$part || $part & self::PART_UNION) {
            $this->unions = $this->builderFactory->create('union');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        if (empty($this->columns)) {
            $this->columns = ['*'];
        }

        return 'SELECT'
               . $this->buildDistinct($this->distinct)
               . $this->buildColumns($this->columns)
               . $this->buildFroms($this->tables)
               . $this->buildJoins()
               . $this->buildWhere()
               . $this->buildGroupBy()
               . $this->buildHaving()
               . $this->buildOrder()
               . $this->buildLimit()
               . $this->buildUnions($this->unions)
               . ';';
    }

    /**
     * Build the distinct clause.
     *
     * @param bool $value
     * @return string
     */
    protected function buildDistinct($value)
    {
        return ($value) ? ' DISTINCT' : '';
    }

    /**
     * Build from clauses.
     *
     * @param array $tables
     * @return string
     */
    protected function buildFroms(array $tables)
    {
        $value = '';

        if (!empty($tables)) {
            $values = [];
            foreach ($tables as $alias => $table) {
                $values[] = (is_string($alias) && $alias !== '') ? "$table AS $alias" : "$table";
            }
            $value .= ' FROM ' . implode(', ', $values);
        }

        return $value;
    }

    /**
     * Build join clauses.
     *
     * @return string
     */
    protected function buildJoins()
    {
        $value = '';

        foreach ($this->joins as $join) {
            $joinValue = $join->build();

            if ($joinValue !== '') {
                $value .= ' ' . $joinValue;
            }
        }

        return $value;
    }

    /**
     * Build a group by clause.
     *
     * @return string
     */
    protected function buildGroupBy()
    {
        $groups = $this->groups->build();

        return $groups !== '' ? ' ' . $groups : '';
    }

    /**
     * Build the order clause.
     *
     * @return sring
     */
    protected function buildOrder()
    {
        $orders = $this->orders->build();

        return $orders !== '' ? ' ' . $orders : '';
    }

    /**
     * Build the limit clause.
     *
     * @return string
     */
    protected function buildLimit()
    {
        $limit = $this->limit->build();

        return $limit !== '' ? ' ' . $limit : '';
    }

    /**
     * Build union clauses.
     *
     * @param array $unions
     * @return string
     */
    protected function buildUnions(array $unions)
    {
        $value = '';

        foreach ($unions as $union) {
            $value .= ($union['all']) ? " UNION ALL {$union['query']}" : " UNION {$union['query']}";
        }

        return $value;
    }
}
