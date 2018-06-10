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
use Guvra\Builder\BuilderFactoryInterface;
use Guvra\Builder\BuilderInterface;
use Guvra\ConnectionInterface;

/**
 * Join group builder
 */
class JoinGroup extends Builder implements \IteratorAggregate, \Countable
{
    /**
     * @var BuilderFactoryInterface
     */
    protected $builderFactory;

    /**
     * @var array
     */
    protected $joins = [];

    /**
     * @param ConnectionInterface $connection
     * @param BuilderFactoryInterface|null $builderFactory
     */
    public function __construct(ConnectionInterface $connection, BuilderFactoryInterface $builderFactory = null)
    {
        parent::__construct($connection);
        $this->builderFactory = $builderFactory;
    }

    /**
     * Add a join to the group.
     *
     * @param Join $join
     * @return $this
     */
    public function addJoin(Join $join)
    {
        $this->joins[] = $join;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $result = '';
        $first = true;

        foreach ($this as $join) {
            $compiledJoin = $join->build();

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
