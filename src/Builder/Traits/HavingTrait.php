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
     * @var \Guvra\Builder\Clause\Where
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
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function havingExists($query)
    {
        $this->getHavingBuilder()->whereExists($query);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orHavingExists($query)
    {
        $this->getHavingBuilder()->orWhereExists($query);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function havingNotExists($query)
    {
        $this->getHavingBuilder()->whereNotExists($query);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orHavingNotExists($query)
    {
        $this->getHavingBuilder()->orWhereNotExists($query);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function havingIsNull($column)
    {
        $this->getHavingBuilder()->whereIsNull($column);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orHavingIsNull($column)
    {
        $this->getHavingBuilder()->orWhereIsNull($column);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function havingNotNull($column)
    {
        $this->getHavingBuilder()->whereNotNull($column);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orHavingNotNull($column)
    {
        $this->getHavingBuilder()->orWhereNotNull($column);

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
        $this->getHavingBuilder()->whereBetween($column, $lowest, $highest);

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
        $this->getHavingBuilder()->orWhereBetween($column, $lowest, $highest);

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
        $this->getHavingBuilder()->whereNotBetween($column, $lowest, $highest);

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
        $this->getHavingBuilder()->orWhereNotBetween($column, $lowest, $highest);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function havingIn($column, array $values)
    {
        $this->getHavingBuilder()->whereIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orHavingIn($column, array $values)
    {
        $this->getHavingBuilder()->orWhereIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function havingNotIn($column, array $values)
    {
        $this->getHavingBuilder()->whereNotIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orHavingNotIn($column, array $values)
    {
        $this->getHavingBuilder()->orWhereNotIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function havingInSet($column, array $values)
    {
        $this->getHavingBuilder()->whereInSet($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orHavingInSet($column, array $values)
    {
        $this->getHavingBuilder()->orWhereInSet($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function havingNotInSet($column, array $values)
    {
        $this->getHavingBuilder()->whereNotInSet($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orHavingNotInSet($column, array $values)
    {
        $this->getHavingBuilder()->orWhereNotInSet($column, $values);

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

        $havingOutput = $this->havingBuilder->build();

        return $havingOutput !== '' ? ' ' . $havingOutput : '';
    }

    /**
     * @return \Guvra\Builder\Clause\Where
     */
    protected function getHavingBuilder()
    {
        if (!$this->havingBuilder) {
            $this->havingBuilder = $this->builderFactory->create('having', $this->builderFactory);
        }

        return $this->havingBuilder;
    }
}
