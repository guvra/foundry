<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Traits;

use Guvra\Builder\BuilderInterface;
use Guvra\Builder\Clause\ConditionGroup;

/**
 * Having trait.
 */
trait HasHaving
{
    /**
     * @var ConditionGroup
     */
    protected $havingBuilder;

    /**
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function having($column, $operator = null, $value = null)
    {
        $this->getHavingBuilder()->where($column, $operator, $value);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function orHaving($column, $operator = null, $value = null)
    {
        $this->getHavingBuilder()->orWhere($column, $operator, $value);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function havingExists($query)
    {
        $this->getHavingBuilder()->where($query, 'exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function orHavingExists($query)
    {
        $this->getHavingBuilder()->orWhere($query, 'exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function havingNotExists($query)
    {
        $this->getHavingBuilder()->where($query, 'not exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function orHavingNotExists($query)
    {
        $this->getHavingBuilder()->orWhere($query, 'not exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function havingNull($column)
    {
        $this->getHavingBuilder()->where($column, 'is null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function orHavingNull($column)
    {
        $this->getHavingBuilder()->orWhere($column, 'is null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function havingNotNull($column)
    {
        $this->getHavingBuilder()->where($column, 'is not null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function orHavingNotNull($column)
    {
        $this->getHavingBuilder()->orWhere($column, 'is not null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function havingBetween($column, $lowest, $highest)
    {
        $this->getHavingBuilder()->where($column, 'between', [$lowest, $highest]);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function orHavingBetween($column, $lowest, $highest)
    {
        $this->getHavingBuilder()->orWhere($column, 'between', [$lowest, $highest]);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function havingNotBetween($column, $lowest, $highest)
    {
        $this->getHavingBuilder()->where($column, 'not between', [$lowest, $highest]);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function orHavingNotBetween($column, $lowest, $highest)
    {
        $this->getHavingBuilder()->orWhere($column, 'not between', [$lowest, $highest]);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $values
     * @return $this
     */
    public function havingIn($column, $values)
    {
        $this->getHavingBuilder()->where($column, 'in', $values);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $values
     * @return $this
     */
    public function orHavingIn($column, $values)
    {
        $this->getHavingBuilder()->orWhere($column, 'in', $values);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $values
     * @return $this
     */
    public function havingNotIn($column, $values)
    {
        $this->getHavingBuilder()->where($column, 'not in', $values);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $values
     * @return $this
     */
    public function orHavingNotIn($column, $values)
    {
        $this->getHavingBuilder()->orWhere($column, 'not in', $values);
        $this->compiled = null;

        return $this;
    }

    /**
     * @return string
     */
    protected function buildHaving()
    {
        if (!$this->havingBuilder) {
            return '';
        }

        $result = $this->havingBuilder->toString();

        return $result !== '' ? ' HAVING ' . $result : '';
    }

    /**
     * @return ConditionGroup
     */
    protected function getHavingBuilder()
    {
        if (!$this->havingBuilder) {
            $this->havingBuilder = $this->builderFactory->create('conditionGroup');
        }

        return $this->havingBuilder;
    }
}
