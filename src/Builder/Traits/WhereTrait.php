<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Traits;

use Guvra\Builder\BuilderInterface;

/**
 * Where trait.
 */
trait WhereTrait
{
    /**
     * @var \Guvra\Builder\Clause\ConditionGroup
     */
    protected $whereBuilder;

    /**
     * @param BuilderInterface|string|callable $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where($column, $operator = null, $value = null)
    {
        $this->getWhereBuilder()->where($column, $operator, $value);

        return $this;
    }

    /**
     * @param BuilderInterface|string|callable $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        $this->getWhereBuilder()->orWhere($column, $operator, $value);

        return $this;
    }

    /**
     * @param BuilderInterface|string $condition $value
     * @return $this
     */
    public function whereRaw($condition)
    {
        $this->getWhereBuilder()->where($condition);

        return $this;
    }

    /**
     * @param BuilderInterface|string $condition $value
     * @return $this
     */
    public function orWhereRaw($condition)
    {
        $this->getWhereBuilder()->orWhere($condition);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function whereExists($query)
    {
        $this->getWhereBuilder()->where($query, 'exists');

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orWhereExists($query)
    {
        $this->getWhereBuilder()->orWhere($query, 'exists');

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function whereNotExists($query)
    {
        $this->getWhereBuilder()->where($query, 'not exists');

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orWhereNotExists($query)
    {
        $this->getWhereBuilder()->orWhere($query, 'not exists');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function whereIsNull($column)
    {
        $this->getWhereBuilder()->where($query, 'is null');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orWhereIsNull($column)
    {
        $this->getWhereBuilder()->orWhere($query, 'is null');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function whereNotNull($column)
    {
        $this->getWhereBuilder()->where($query, 'is not null');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orWhereNotNull($column)
    {
        $this->getWhereBuilder()->orWhere($query, 'is not null');

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function whereBetween($column, $lowest, $highest)
    {
        $this->getWhereBuilder()->where($column, 'between', [$lowest, $highest]);

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function orWhereBetween($column, $lowest, $highest)
    {
        $this->getWhereBuilder()->orWhere($column, 'between', [$lowest, $highest]);

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function whereNotBetween($column, $lowest, $highest)
    {
        $this->getWhereBuilder()->where($column, 'not between', [$lowest, $highest]);

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function orWhereNotBetween($column, $lowest, $highest)
    {
        $this->getWhereBuilder()->orWhere($column, 'not between', [$lowest, $highest]);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereIn($column, array $values)
    {
        $this->getWhereBuilder()->where($column, 'in', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereIn($column, array $values)
    {
        $this->getWhereBuilder()->orWhere($column, 'in', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereNotIn($column, array $values)
    {
        $this->getWhereBuilder()->where($column, 'not in', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereNotIn($column, array $values)
    {
        $this->getWhereBuilder()->orWhere($column, 'not in', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereInSet($column, array $values)
    {
        $this->getWhereBuilder()->where($column, 'in set', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereInSet($column, array $values)
    {
        $this->getWhereBuilder()->orWhere($column, 'in set', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereNotInSet($column, array $values)
    {
        $this->getWhereBuilder()->where($column, 'not in set', $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereNotInSet($column, array $values)
    {
        $this->getWhereBuilder()->orWhere($column, 'not in set', $values);

        return $this;
    }

    /**
     * @return string
     */
    protected function buildWhere()
    {
        if (!$this->whereBuilder) {
            return '';
        }

        $result = $this->whereBuilder->build();

        return $result !== '' ? ' WHERE ' . $result : '';
    }

    /**
     * @return \Guvra\Builder\Clause\ConditionGroup
     */
    protected function getWhereBuilder()
    {
        if (!$this->whereBuilder) {
            $this->whereBuilder = $this->builderFactory->create('conditionGroup', $this->builderFactory);
        }

        return $this->whereBuilder;
    }
}
