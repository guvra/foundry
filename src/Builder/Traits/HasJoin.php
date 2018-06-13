<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Traits;

use Guvra\Builder\Clause\Condition;
use Guvra\Builder\Clause\JoinGroup;
use Guvra\Builder\Expression;

/**
 * Join trait.
 */
trait HasJoin
{
    /**
     * @var JoinGroup
     */
    protected $joinGroupBuilder;

    /**
     * Add an inner join clause to the query.
     *
     * @param string|array $table
     * @param array $args
     * @return $this
     */
    public function join($table, ...$args)
    {
        $condition = $this->prepareJoinCondition($args);
        $this->getJoinGroupBuilder()->join($table, $condition);
        $this->compiled = null;

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
        $this->getJoinGroupBuilder()->joinLeft($table, $condition);
        $this->compiled = null;

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
        $this->getJoinGroupBuilder()->joinRight($table, $condition);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a cross join clause to the query.
     *
     * @param string|array $table
     * @return $this
     */
    public function joinCross($table)
    {
        $this->getJoinGroupBuilder()->joinCross($table);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a natural join clause to the query.
     *
     * @param string|array $table
     * @return $this
     */
    public function joinNatural($table)
    {
        $this->getJoinGroupBuilder()->joinNatural($table);
        $this->compiled = null;

        return $this;
    }

    /**
     * Build join clauses.
     *
     * @return string
     */
    protected function buildJoins()
    {
        $result = $this->getJoinGroupBuilder()->toString();

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

        return $this->builderFactory->create('condition', $column, $operator, $value);
    }

    /**
     * @return JoinGroup
     */
    protected function getJoinGroupBuilder()
    {
        if (!$this->joinGroupBuilder) {
            $this->joinGroupBuilder = $this->builderFactory->create('joinGroup');
        }

        return $this->joinGroupBuilder;
    }
}
