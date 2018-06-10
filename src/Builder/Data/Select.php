<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
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
    use \Guvra\Builder\Traits\JoinTrait;

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
     * @var array
     */
    protected $groups = [];

    /**
     * @var array
     */
    protected $orders = [];

    /**
     * @var array
     */
    protected $limit = ['start' => 0, 'max' => 0];

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
            $tables = [$tables];
        }

        $this->tables = $tables;

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

        $this->groups = array_unique(array_merge($this->columns, $columns));

        return $this;
    }

    /**
     * Add an order by clause to the query.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function order(string $column, string $direction = 'ASC')
    {
        $this->orders[] = compact('column', 'direction');

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
        $this->limit = compact('max', 'start');

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
        if ($this->unionBuilder === null) {
            $this->unionBuilder = $this->builderFactory->create('selectOrderGroup');
        }

        $this->unions[] = compact('query', 'all');

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
            . $this->buildDistinct()
            . $this->buildColumns()
            . $this->buildFroms()
            . $this->buildJoins()
            . $this->buildWhere()
            . $this->buildGroupBy()
            . $this->buildHaving()
            . $this->buildOrder()
            . $this->buildLimit()
            . $this->buildUnions();
    }

    /**
     * Build the distinct clause.
     *
     * @param bool $value
     * @return string
     */
    protected function buildDistinct()
    {
        return $this->distinct ? ' DISTINCT' : '';
    }

    /**
     * @return string
     */
    protected function buildColumns()
    {
        if (empty($this->columns)) {
            return '';
        }

        $values = [];

        foreach ($this->columns as $alias => $column) {
            if (is_object($column) && $column instanceof Builder) {
                $column = "({$column->build()})";
            }

            $values[] = is_string($alias) && $alias !== '' ? "$column AS $alias" : "$column";
        }

        return ' ' . implode(', ', $values);
    }

    /**
     * @return string
     */
    protected function buildFroms()
    {
        if (empty($this->tables)) {
            return '';
        }

        $values = [];
        foreach ($this->tables as $alias => $table) {
            $values[] = (is_string($alias) && $alias !== '') ? "$table AS $alias" : "$table";
        }

        return ' FROM ' . implode(', ', $values);
    }

    /**
     * Build a group by clause.
     *
     * @return string
     */
    protected function buildGroupBy()
    {
        if (empty($this->groups)) {
            return '';
        }

        return ' GROUP BY ' . implode(', ', $this->groups);
    }

    /**
     * Build the order clause.
     *
     * @return sring
     */
    protected function buildOrder()
    {
        if (empty($this->orders)) {
            return '';
        }

        $parts = [];

        foreach ($this->orders as $order) {
            $column = $order['column'];
            $direction = $order['direction'];
            $parts[] = "$column $direction";
        }

        return ' ORDER BY ' . implode(', ', $parts);
    }

    /**
     * Build the limit clause.
     *
     * @return string
     */
    protected function buildLimit()
    {
        if ($this->limit['max'] === 0 && $this->limit['start'] === 0) {
            return '';
        }

        return $this->limit['start'] > 0
            ? " LIMIT {$this->limit['max']} OFFSET {$this->limit['start']}"
            : " LIMIT {$this->limit['max']}";
    }

    /**
     * Build union clauses.
     *
     * @return string
     */
    protected function buildUnions()
    {
        $value = '';
        foreach ($this->unions as $union) {
            $value .= ($union['all']) ? " union all {$union['query']}" : " union {$union['query']}";
        }

        return $value;
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
            $this->joinGroupBuilder = null;
        }

        if (!$part || $part & self::PART_WHERE) {
            $this->whereBuilder = null;
        }

        if (!$part || $part & self::PART_GROUP) {
            $this->groups = [];
        }

        if (!$part || $part & self::PART_HAVING) {
            $this->havingBuilder = null;
        }

        if (!$part || $part & self::PART_ORDER) {
            $this->orders = [];
        }

        if (!$part || $part & self::PART_LIMIT) {
            $this->limit = ['start' => 0, 'max' => 0];
        }

        if (!$part || $part & self::PART_UNION) {
            $this->unions = [];
        }

        return $this;
    }

    /**
     * Get a part of the query.
     *
     * @param int $part
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public function getPart(int $part)
    {
        if ($part & self::PART_COLUMNS) {
            return $this->columns;
        }

        if ($part & self::PART_DISTINCT) {
            return $this->distinct;
        }

        if ($part & self::PART_FROM) {
            return $this->tables;
        }

        if ($part & self::PART_JOIN) {
            return $this->joinGroupBuilder;
        }

        if ($part & self::PART_WHERE) {
            return $this->whereBuilder;
        }

        if ($part & self::PART_GROUP) {
            return $this->groups;
        }

        if ($part & self::PART_HAVING) {
            return $this->havingBuilder;
        }

        if ($part & self::PART_ORDER) {
            return $this->orders;
        }

        if ($part & self::PART_LIMIT) {
            return $this->limit;
        }

        if ($part & self::PART_UNION) {
            return $this->unions;
        }

        throw new \UnexpectedValueException(sprint('The query part "%s" does not exist.', $part));
    }
}
