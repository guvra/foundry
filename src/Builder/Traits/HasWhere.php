<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Traits;

/**
 * Where trait.
 */
trait HasWhere
{
    /**
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where($column, $operator = null, $value = null)
    {
        $this->getPart('where')->where($column, $operator, $value);
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
        $this->getPart('where')->orWhere($column, $operator, $value);
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function whereExists($query)
    {
        $this->getPart('where')->where($query, 'exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function orWhereExists($query)
    {
        $this->getPart('where')->orWhere($query, 'exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function whereNotExists($query)
    {
        $this->getPart('where')->where($query, 'not exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $query
     * @return $this
     */
    public function orWhereNotExists($query)
    {
        $this->getPart('where')->orWhere($query, 'not exists');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function whereNull($column)
    {
        $this->getPart('where')->where($column, 'is null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function orWhereNull($column)
    {
        $this->getPart('where')->orWhere($column, 'is null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function whereNotNull($column)
    {
        $this->getPart('where')->where($column, 'is not null');
        $this->compiled = null;

        return $this;
    }

    /**
     * @param mixed $column
     * @return $this
     */
    public function orWhereNotNull($column)
    {
        $this->getPart('where')->orWhere($column, 'is not null');
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
        $this->getPart('where')->where($column, 'between', [$lowest, $highest]);
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
        $this->getPart('where')->orWhere($column, 'between', [$lowest, $highest]);
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
        $this->getPart('where')->where($column, 'not between', [$lowest, $highest]);
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
        $this->getPart('where')->orWhere($column, 'not between', [$lowest, $highest]);
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
        $this->getPart('where')->where($column, 'in', $values);
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
        $this->getPart('where')->orWhere($column, 'in', $values);
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
        $this->getPart('where')->where($column, 'not in', $values);
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
        $this->getPart('where')->orWhere($column, 'not in', $values);
        $this->compiled = null;

        return $this;
    }
}
