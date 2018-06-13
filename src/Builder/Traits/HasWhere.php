<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Traits;

use Guvra\Builder\Clause\ConditionGroup;

/**
 * Where trait.
 */
trait HasWhere
{
    /**
     * @var ConditionGroup
     */
    protected $whereBuilder;

    /**
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where($column, $operator = null, $value = null)
    {
        $this->getWhereBuilder()->where($column, $operator, $value);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        $this->getWhereBuilder()->orWhere($column, $operator, $value);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function whereExists($query)
    {
        $this->getWhereBuilder()->where($query, 'exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function orWhereExists($query)
    {
        $this->getWhereBuilder()->orWhere($query, 'exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function whereNotExists($query)
    {
        $this->getWhereBuilder()->where($query, 'not exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function orWhereNotExists($query)
    {
        $this->getWhereBuilder()->orWhere($query, 'not exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function whereNull($column)
    {
        $this->getWhereBuilder()->where($column, 'is null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function orWhereNull($column)
    {
        $this->getWhereBuilder()->orWhere($column, 'is null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function whereNotNull($column)
    {
        $this->getWhereBuilder()->where($column, 'is not null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function orWhereNotNull($column)
    {
        $this->getWhereBuilder()->orWhere($column, 'is not null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function whereBetween($column, $lowest, $highest)
    {
        $this->getWhereBuilder()->where($column, 'between', [$lowest, $highest]);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function orWhereBetween($column, $lowest, $highest)
    {
        $this->getWhereBuilder()->orWhere($column, 'between', [$lowest, $highest]);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function whereNotBetween($column, $lowest, $highest)
    {
        $this->getWhereBuilder()->where($column, 'not between', [$lowest, $highest]);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return $this
     */
    public function orWhereNotBetween($column, $lowest, $highest)
    {
        $this->getWhereBuilder()->orWhere($column, 'not between', [$lowest, $highest]);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $values
     * @return $this
     */
    public function whereIn($column, $values)
    {
        $this->getWhereBuilder()->where($column, 'in', $values);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $values
     * @return $this
     */
    public function orWhereIn($column, $values)
    {
        $this->getWhereBuilder()->orWhere($column, 'in', $values);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $values
     * @return $this
     */
    public function whereNotIn($column, $values)
    {
        $this->getWhereBuilder()->where($column, 'not in', $values);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @param mixed $values
     * @return $this
     */
    public function orWhereNotIn($column, $values)
    {
        $this->getWhereBuilder()->orWhere($column, 'not in', $values);
        $this->compiled = null;

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

        $result = $this->whereBuilder->toString();

        return $result !== '' ? ' WHERE ' . $result : '';
    }

    /**
     * @return ConditionGroup
     */
    protected function getWhereBuilder()
    {
        if (!$this->whereBuilder) {
            $this->whereBuilder = $this->builderFactory->create('conditionGroup');
        }

        return $this->whereBuilder;
    }
}
