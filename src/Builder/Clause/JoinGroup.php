<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\Builder;
use Guvra\Builder\BuilderInterface;
use Guvra\ConnectionInterface;

/**
 * Join group builder
 */
class JoinGroup extends Builder
{
    /**
     * @var BuilderInterface[]
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
    public function join($table, Condition $condition = null)
    {
        $join = $this->builderFactory->create('join', Join::TYPE_INNER, $table, $condition);

        return $this->addJoin($join);
    }

    /**
     * Add a left join to the group.
     *
     * @param string|array $table
     * @param Condition|null $condition
     * @return $this
     */
    public function joinLeft($table, Condition $condition = null)
    {
        $join = $this->builderFactory->create('join', Join::TYPE_LEFT, $table, $condition);

        return $this->addJoin($join);
    }

    /**
     * Add a right join to the group.
     *
     * @param string|array $table
     * @param Condition|null $condition
     * @return $this
     */
    public function joinRight($table, Condition $condition = null)
    {
        $join = $this->builderFactory->create('join', Join::TYPE_RIGHT, $table, $condition);

        return $this->addJoin($join);
    }

    /**
     * Add a cross join to the group.
     *
     * @param string|array $table
     * @return $this
     */
    public function joinCross($table)
    {
        $join = $this->builderFactory->create('join', Join::TYPE_CROSS, $table);

        return $this->addJoin($join);
    }

    /**
     * Add a natural join to the group.
     *
     * @param string|array $table
     * @return $this
     */
    public function joinNatural($table)
    {
        $join = $this->builderFactory->create('join', Join::TYPE_NATURAL, $table);

        return $this->addJoin($join);
    }

    /**
     * Add a join to the group.
     *
     * @param BuilderInterface $join
     * @return $this
     */
    public function addJoin(BuilderInterface $join)
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

        foreach ($this->joins as $join) {
            $compiledJoin = $join->toString();
            $result .= $first ? $compiledJoin : ' ' . $compiledJoin;
            $first = false;
        }

        return $result;
    }
}
