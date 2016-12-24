<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Traits;

/**
 * Join trait.
 */
trait JoinTrait
{
    /**
     * @var \Guvra\Builder\Clause\Join[]
     */
    protected $joins = [];

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
        $joinBuilder = $this->builderFactory->create('join', $this->builderFactory);
        $this->joins[] = $joinBuilder->join('inner', $table, ...$args);

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
        $joinBuilder = $this->builderFactory->create('join', $this->builderFactory);
        $this->joins[] = $joinBuilder->join('left', $table, ...$args);

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
        $joinBuilder = $this->builderFactory->create('join', $this->builderFactory);
        $this->joins[] = $joinBuilder->join('right', $table, ...$args);

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
        $joinBuilder = $this->builderFactory->create('join', $this->builderFactory);
        $this->joins[] = $joinBuilder->join('cross', $table);

        return $this;
    }
}
