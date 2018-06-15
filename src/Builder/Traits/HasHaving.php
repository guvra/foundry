<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Traits;

/**
 * Having trait.
 */
trait HasHaving
{
    /**
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function having($column, $operator = null, $value = null)
    {
        $this->getPart('having')->where($column, $operator, $value);
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
        $this->getPart('having')->orWhere($column, $operator, $value);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function havingExists($query)
    {
        $this->getPart('having')->where($query, 'exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function orHavingExists($query)
    {
        $this->getPart('having')->orWhere($query, 'exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function havingNotExists($query)
    {
        $this->getPart('having')->where($query, 'not exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function orHavingNotExists($query)
    {
        $this->getPart('having')->orWhere($query, 'not exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function havingNull($column)
    {
        $this->getPart('having')->where($column, 'is null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function orHavingNull($column)
    {
        $this->getPart('having')->orWhere($column, 'is null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function havingNotNull($column)
    {
        $this->getPart('having')->where($column, 'is not null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function orHavingNotNull($column)
    {
        $this->getPart('having')->orWhere($column, 'is not null');
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
        $this->getPart('having')->where($column, 'between', [$lowest, $highest]);
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
        $this->getPart('having')->orWhere($column, 'between', [$lowest, $highest]);
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
        $this->getPart('having')->where($column, 'not between', [$lowest, $highest]);
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
        $this->getPart('having')->orWhere($column, 'not between', [$lowest, $highest]);
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
        $this->getPart('having')->where($column, 'in', $values);
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
        $this->getPart('having')->orWhere($column, 'in', $values);
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
        $this->getPart('having')->where($column, 'not in', $values);
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
        $this->getPart('having')->orWhere($column, 'not in', $values);
        $this->compiled = null;

        return $this;
    }
}
