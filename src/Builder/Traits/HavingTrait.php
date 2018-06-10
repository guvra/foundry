<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Traits;

use Guvra\Builder\BuilderInterface;

/**
 * Having trait.
 */
trait HavingTrait
{
    /**
     * @var \Guvra\Builder\Clause\ConditionGroup
     */
    protected $havingBuilder;

    /**
     * @param BuilderInterface|string|callable $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function having($column, $operator = null, $value = null)
    {
        $this->getHavingBuilder()->where($column, $operator, $value);

        return $this;
    }

    /**
     * @param BuilderInterface|string|callable $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function orHaving($column, $operator = null, $value = null)
    {
        $this->getHavingBuilder()->orWhere($column, $operator, $value);

        return $this;
    }

    /**
     * @param BuilderInterface|string $condition $value
     * @return $this
     */
    public function havingRaw($condition)
    {
        $this->getHavingBuilder()->where($condition);

        return $this;
    }

    /**
     * @param BuilderInterface|string $condition $value
     * @return $this
     */
    public function orHavingRaw($condition)
    {
        $this->getHavingBuilder()->orWhere($condition);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function havingExists($query)
    {
        $this->getHavingBuilder()->where($query, 'exists');

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orHavingExists($query)
    {
        $this->getHavingBuilder()->orWhere($query, 'exists');

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function havingNotExists($query)
    {
        $this->getHavingBuilder()->where($query, 'not exists');

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orHavingNotExists($query)
    {
        $this->getHavingBuilder()->orWhere($query, 'not exists');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function havingIsNull($column)
    {
        $this->getHavingBuilder()->where($query, 'is null');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orHavingIsNull($column)
    {
        $this->getHavingBuilder()->orWhere($query, 'is null');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function havingNotNull($column)
    {
        $this->getHavingBuilder()->where($query, 'is not null');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orHavingNotNull($column)
    {
        $this->getHavingBuilder()->orWhere($query, 'is not null');

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function havingBetween($column, $lowest, $highest)
    {
        $this->getHavingBuilder()->where($column, 'between', [$lowest, $highest]);

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function orHavingBetween($column, $lowest, $highest)
    {
        $this->getHavingBuilder()->orWhere($column, 'between', [$lowest, $highest]);

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function havingNotBetween($column, $lowest, $highest)
    {
        $this->getHavingBuilder()->where($column, 'not between', [$lowest, $highest]);

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function orHavingNotBetween($column, $lowest, $highest)
    {
        $this->getHavingBuilder()->orWhere($column, 'not between', [$lowest, $highest]);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function havingIn($column, array $values)
    {
        $this->getHavingBuilder()->where($column, 'in', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orHavingIn($column, array $values)
    {
        $this->getHavingBuilder()->orWhere($column, 'in', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function havingNotIn($column, array $values)
    {
        $this->getHavingBuilder()->where($column, 'not in', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orHavingNotIn($column, array $values)
    {
        $this->getHavingBuilder()->orWhere($column, 'not in', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function havingInSet($column, array $values)
    {
        $this->getHavingBuilder()->where($column, 'in set', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orHavingInSet($column, array $values)
    {
        $this->getHavingBuilder()->orWhere($column, 'in set', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function havingNotInSet($column, array $values)
    {
        $this->getHavingBuilder()->where($column, 'not in set', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orHavingNotInSet($column, array $values)
    {
        $this->getHavingBuilder()->orWhere($column, 'not in set', $values);

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

        $result = $this->havingBuilder->build();

        return $result !== '' ? ' HAVING ' . $result : '';
    }

    /**
     * @return \Guvra\Builder\Clause\ConditionGroup
     */
    protected function getHavingBuilder()
    {
        if (!$this->havingBuilder) {
            $this->havingBuilder = $this->builderFactory->create('conditionGroup', $this->builderFactory);
        }

        return $this->havingBuilder;
    }
}
