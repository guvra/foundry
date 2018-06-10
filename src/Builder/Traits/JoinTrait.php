<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Traits;

use \Guvra\Builder\Clause\Condition;
use \Guvra\Builder\Expression;

/**
 * Join trait.
 */
trait JoinTrait
{
    /**
     * @var \Guvra\Builder\Clause\JoinGroup
     */
    protected $joinGroupBuilder;

    /**
     * Add a join clause to the query.
     *
     * @param string|array $table
     * @param array $args
     * @return $this
     */
    public function join($table, ...$args)
    {
        $condition = $this->prepareJoinCondition($args);
        $joinBuilder = $this->builderFactory->create('join', 'inner', $table, $condition);
        $this->getJoinGroupBuilder()->addJoin($joinBuilder);

        return $this;
    }

    /**
     * Add a left join clause to the query.
     *
     * @param string|array $table
     * @param array $args
     * @return $this
     */
    public function joinLeft($table, ...$args)
    {
        $condition = $this->prepareJoinCondition($args);
        $joinBuilder = $this->builderFactory->create('join', 'left', $table, $condition);
        $this->getJoinGroupBuilder()->addJoin($joinBuilder);

        return $this;
    }

    /**
     * Add a right join clause to the query.
     *
     * @param string|array $table
     * @param array $args
     * @return $this
     */
    public function joinRight($table, ...$args)
    {
        $condition = $this->prepareJoinCondition($args);
        $joinBuilder = $this->builderFactory->create('join', 'right', $table, $condition);
        $this->getJoinGroupBuilder()->addJoin($joinBuilder);

        return $this;
    }

    /**
     * Add a left join clause to the query.
     *
     * @param string|array $table
     * @return $this
     */
    public function joinCross($table)
    {
        $joinBuilder = $this->builderFactory->create('join', 'cross', $table);
        $this->getJoinGroupBuilder()->addJoin($joinBuilder);

        return $this;
    }

    /**
     * Build join clauses.
     *
     * @return string
     */
    protected function buildJoins()
    {
        $result = $this->getJoinGroupBuilder()->build();

        return $result !== '' ? ' ' . $result : '';
    }

    /**
     * @param array $args
     * @return Condition|null
     */
    protected function prepareJoinCondition(array $args)
    {
        if (empty($args)) {
            return null;
        }

        $column = $args[0];
        $operator = isset($args[1]) ? $args[1] : null;
        $value = isset($args[2]) ? new Expression($args[2]) : null;

        return $this->connection
            ->getBuilderFactory()
            ->create('condition', $column, $operator, $value);
    }

    /**
     * @return \Guvra\Builder\Clause\JoinGroup
     */
    protected function getJoinGroupBuilder()
    {
        if (!$this->joinGroupBuilder) {
            $this->joinGroupBuilder = $this->builderFactory->create('joinGroup', $this->builderFactory);
        }

        return $this->joinGroupBuilder;
    }
}
