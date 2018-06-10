<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\Builder;
use Guvra\Builder\BuilderFactory;
use Guvra\Builder\BuilderInterface;
use Guvra\ConnectionInterface;

/**
 * Join group builder
 */
class JoinGroup extends Builder implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    protected $joins = [];

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    /**
     * Add an inner join to the group.
     *
     * @param string|array $table
     * @param Condition|null $condition
     * @return $this
     */
    public function join($table, Condition $condition)
    {
        $join = $this->connection->getBuilderFactory()->create('join', 'inner', $table, $condition);

        return $this->addJoin($join);
    }

    /**
     * Add a left join to the group.
     *
     * @param string|array $table
     * @param Condition|null $condition
     * @return $this
     */
    public function joinLeft($table, Condition $condition)
    {
        $join = $this->connection->getBuilderFactory()->create('join', 'left', $table, $condition);

        return $this->addJoin($join);
    }

    /**
     * Add a right join to the group.
     *
     * @param string|array $table
     * @param Condition|null $condition
     * @return $this
     */
    public function joinRight($table, Condition $condition)
    {
        $join = $this->connection->getBuilderFactory()->create('join', 'right', $table, $condition);

        return $this->addJoin($join);
    }

    /**
     * Add a cross join to the group.
     *
     * @param string|array $table
     * @param Condition|null $condition
     * @return $this
     */
    public function joinCross($table)
    {
        $join = $this->connection->getBuilderFactory()->create('cross', 'cross', $table);

        return $this->addJoin($join);
    }

    /**
     * Add a natural join to the group.
     *
     * @param string|array $table
     * @param Condition|null $condition
     * @return $this
     */
    public function joinNatural($table)
    {
        $join = $this->connection->getBuilderFactory()->create('join', 'natural', $table);

        return $this->addJoin($join);
    }

    /**
     * Add a join to the group.
     *
     * @param string|array $table
     * @param Condition|null $condition
     * @return $this
     */
    public function addJoin(Join $join)
    {
        $this->joins[] = $join;
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        $result = '';
        $first = true;

        foreach ($this as $join) {
            $compiledJoin = $join->toString();

            if ($compiledJoin === '') {
                continue;
            }

            $result .= $first ? $compiledJoin : ' ' . $compiledJoin;
            $first = false;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->joins);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->conditions);
    }
}
