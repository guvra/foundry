<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Statement;

use Guvra\Builder\Builder;
use Guvra\Builder\BuilderInterface;
use Guvra\Builder\Traits\HasHaving;
use Guvra\Builder\Traits\HasJoin;
use Guvra\Builder\Traits\HasWhere;
use Guvra\ConnectionInterface;

/**
 * Select builder.
 */
class Select extends Builder
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

    use HasJoin;
    use HasWhere;
    use HasHaving;

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
     */
    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

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

        $this->columns = $columns;
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
        $this->distinct = $value;
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

        $this->tables = $tables;
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

        $this->groups = array_unique(array_merge($this->groups, $columns));
        $this->compiled = null;

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
        $this->limit = compact('max', 'start');
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a union clause to the query.
     *
     * @param BuilderInterface|string $query
     * @param bool $all
     * @return $this
     */
    public function union($query, $all = false)
    {
        $this->unions[] = compact('query', 'all');
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
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
            return ' *';
        }

        $values = [];

        foreach ($this->columns as $alias => $column) {
            if (is_object($column) && $column instanceof Builder) {
                $column = "({$column->toString()})";
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
            $values[] = is_string($alias) && $alias !== '' ? "$table AS $alias" : "$table";
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
     * @return string
     */
    protected function buildOrder()
    {
        if (empty($this->orders)) {
            return '';
        }

        $parts = [];

        foreach ($this->orders as $order) {
            $column = $order['column'];
            $direction = strtoupper($order['direction']);
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

        $parts = [];

        if ($this->limit['max'] > 0) {
            $parts[] = 'LIMIT ' . $this->limit['max'];
        }

        if ($this->limit['start'] > 0) {
            $parts[] = 'OFFSET ' . $this->limit['start'];
        }

        return ' ' . implode(' ', $parts);
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
            $value .= $union['all'] ? " UNION ALL {$union['query']}" : " UNION {$union['query']}";
        }

        return $value;
    }

    /**
     * Reset a part of the query (or the whole query).
     *
     * @param int|null $part
     * @return $this
     */
    public function reset($part = null)
    {
        $this->compiled = null;

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
}
