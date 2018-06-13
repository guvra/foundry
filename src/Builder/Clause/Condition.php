<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\Builder;
use Guvra\Builder\BuilderInterface;
use Guvra\ConnectionInterface;

/**
 * Condition builder.
 */
class Condition extends Builder
{
    /**
     * @var mixed
     */
    protected $column;

    /**
     * @var mixed|null
     */
    protected $operator;

    /**
     * @var mixed|null
     */
    protected $value;

    /**
     * @param ConnectionInterface $connection
     * @param mixed $column
     * @param string|null $operator
     * @param mixed|null $value
     */
    public function __construct(
        ConnectionInterface $connection,
        $column,
        string $operator = null,
        $value = null
    ) {
        parent::__construct($connection);
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        $column = $this->column;
        $operator = $this->operator;
        $value = $this->value;

        // Don't allow empty columns
        if ($column === '' || $column === null) {
            throw new \UnexpectedValueException('The condition column must not be empty');
        }

        // Hard cast the column to string if is a builder object, because __toString must not throw exceptions
        if ($column instanceof BuilderInterface) {
            $column = '(' . $column->toString() . ')';
        }

        // Allow creating complex queries when using a callback as the column
        if (!$operator) {
            return is_callable($column) ? $this->buildCallable($column, 'conditionGroup') : $column;
        }

        // Allow building sub queries with callback functions
        if (is_callable($column)) {
            $column = $this->buildCallable($column, 'select', true);
        }

        return $this->buildCondition($column, $operator, $value);
    }

    /**
     * @param mixed $column
     * @param string $operator
     * @param mixed $value
     * @return string
     */
    protected function buildCondition($column, string $operator, $value)
    {
        switch ($operator) {
            case 'exists':
                return $this->buildExists($column);

            case 'not exists':
                return $this->buildNotExists($column);

            case 'null':
            case 'is null':
                return $this->buildIsNull($column);

            case 'not null':
            case 'is not null':
                return $this->buildIsNotNull($column);

            case 'between':
                return $this->buildBetween($column, $value[0], $value[1]);

            case 'not between':
                return $this->buildNotBetween($column, $value[0], $value[1]);

            case 'in':
                return $this->buildIn($column, $value);

            case 'not in':
                return $this->buildNotIn($column, $value);

            default:
                return $this->buildDefault($column, $operator, $value);
        }
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function buildExists($value)
    {
        return "EXISTS $value";
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function buildNotExists($value)
    {
        return "NOT EXISTS $value";
    }

    /**
     * @param mixed $column
     * @return string
     */
    protected function buildIsNull($column)
    {
        return "$column IS NULL";
    }

    /**
     * @param mixed $column
     * @return string
     */
    protected function buildIsNotNull($column)
    {
        return "$column IS NOT NULL";
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return string
     */
    protected function buildBetween($column, $lowest, $highest)
    {
        $lowest = $this->buildValue($lowest);
        $highest = $this->buildValue($highest);

        return "$column BETWEEN $lowest AND $highest";
    }

    /**
     * @param mixed $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return string
     */
    protected function buildNotBetween($column, $lowest, $highest)
    {
        $lowest = $this->buildValue($lowest);
        $highest = $this->buildValue($highest);

        return "$column NOT BETWEEN $lowest AND $highest";
    }

    /**
     * @param string $column
     * @param mixed $values
     * @return string
     */
    protected function buildIn($column, $values)
    {
        $value = is_array($values) ? '(' . implode(',', $this->escapeValues($values)) . ')' : $this->buildValue($values);

        return "$column IN $value";
    }

    /**
     * @param mixed $column
     * @param mixed $values
     * @return string
     */
    protected function buildNotIn($column, $values)
    {
        $value = is_array($values) ? '(' . implode(',', $this->escapeValues($values)) . ')' : $this->buildValue($values);

        return "$column NOT IN $value";
    }

    /**
     * @param callable $callback
     * @param string $type
     * @param bool $enclose
     * @return string
     */
    protected function buildCallable($callback, string $type, bool $enclose = false)
    {
        $builder = $this->builderFactory->create($type);
        call_user_func($callback, $builder);

        $result = $builder->toString();

        if ($enclose) {
            $result = '(' . $result . ')';
        }

        return $result;
    }

    /**
     * @param mixed $column
     * @param string $operator
     * @param mixed $value
     * @return string
     */
    protected function buildDefault($column, string $operator, $value)
    {
        $operator = strtoupper($operator);
        $value = $this->buildValue($value);

        return "$column $operator $value";
    }

    /**
     * Escape a value.
     *
     * @param mixed $value
     * @return string
     */
    protected function buildValue($value)
    {
        if (is_null($value)) {
            return 'null';
        }

        if (is_callable($value)) {
            return $this->buildCallable($value, 'select', true);
        }

        if ($value instanceof BuilderInterface) {
            return '(' . $value->toString() . ')';
        }

        return $this->escapeValue($value);
    }

    /**
     * Escape a value.
     *
     * @param mixed $value
     * @return string
     */
    protected function escapeValue($value)
    {
        if (is_string($value)) {
            $value = $this->connection->quote($value);
        }

        return $value;
    }

    /**
     * Escape values.
     *
     * @param array $values
     * @return array
     */
    protected function escapeValues(array $values)
    {
        foreach ($values as $key => $value) {
            $escapedValue = $this->escapeValue($value);

            if ($escapedValue !== $value) {
                $values[$key] = $escapedValue;
            }
        }

        return $values;
    }
}
