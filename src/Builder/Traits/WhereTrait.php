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
 * Where trait.
 */
trait WhereTrait
{
    /**
     * @var \Guvra\Builder\Clause\Where
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
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function whereExists($query)
    {
        $this->getWhereBuilder()->whereExists($query);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orWhereExists($query)
    {
        $this->getWhereBuilder()->orWhereExists($query);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function whereNotExists($query)
    {
        $this->getWhereBuilder()->whereNotExists($query);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orWhereNotExists($query)
    {
        $this->getWhereBuilder()->orWhereNotExists($query);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function whereIsNull($column)
    {
        $this->getWhereBuilder()->whereIsNull($column);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orWhereIsNull($column)
    {
        $this->getWhereBuilder()->orWhereIsNull($column);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function whereNotNull($column)
    {
        $this->getWhereBuilder()->whereNotNull($column);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orWhereNotNull($column)
    {
        $this->getWhereBuilder()->orWhereNotNull($column);

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
        $this->getWhereBuilder()->whereBetween($column, $lowest, $highest);

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
        $this->getWhereBuilder()->orWhereBetween($column, $lowest, $highest);

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
        $this->getWhereBuilder()->whereNotBetween($column, $lowest, $highest);

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
        $this->getWhereBuilder()->orWhereNotBetween($column, $lowest, $highest);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereIn($column, array $values)
    {
        $this->getWhereBuilder()->whereIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereIn($column, array $values)
    {
        $this->getWhereBuilder()->orWhereIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereNotIn($column, array $values)
    {
        $this->getWhereBuilder()->whereNotIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereNotIn($column, array $values)
    {
        $this->getWhereBuilder()->orWhereNotIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereInSet($column, array $values)
    {
        $this->getWhereBuilder()->whereInSet($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereInSet($column, array $values)
    {
        $this->getWhereBuilder()->orWhereInSet($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereNotInSet($column, array $values)
    {
        $this->getWhereBuilder()->whereNotInSet($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereNotInSet($column, array $values)
    {
        $this->getWhereBuilder()->orWhereNotInSet($column, $values);

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

        $whereOutput = $this->whereBuilder->build();

        return $whereOutput !== '' ? ' ' . $whereOutput : '';
    }

    /**
     * @return \Guvra\Builder\Clause\Where
     */
    protected function getWhereBuilder()
    {
        if (!$this->whereBuilder) {
            $this->whereBuilder = $this->builderFactory->create('where', $this->builderFactory);
        }

        return $this->whereBuilder;
    }
}
