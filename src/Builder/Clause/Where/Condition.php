<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause\Where;

use Guvra\Builder\AbstractBuilder;
use Guvra\Builder\BuilderFactoryInterface;
use Guvra\Builder\BuilderInterface;
use Guvra\ConnectionInterface;

/**
 * Condition builder.
 */
class Condition extends AbstractBuilder
{
    /**
     * @var BuilderFactoryInterface
     */
    protected $builderFactory;

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
     * @param BuilderFactoryInterface $builderFactory
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     */
    public function __construct(
        ConnectionInterface $connection,
        BuilderFactoryInterface $builderFactory,
        $column,
        $operator = null,
        $value = null
    ) {
        parent::__construct($connection);
        $this->builderFactory = $builderFactory;
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $column = $this->column;
        $operator = $this->operator;
        $value = $this->value;

        if (is_callable($column)) {
            // Execute the callback with a new condition group as a parameter
            return $this->buildCallable($column);
        }

        if (!$operator) {
            // No operator specified, the column param is either a string or a builder object
            return $this->buildRaw($column);
        }

        switch ($operator) {
            case 'exists':
                return $this->buildExists($column);

            case 'not exists':
                return $this->buildNotExists($column);

            case 'is null':
                return $this->buildIsNull($column);

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

            case 'in set':
                return $this->buildInSet($column, $value);

            case 'not in set':
                return $this->buildNotInSet($column, $value);

            default:
                return $this->buildDefault($column, $operator, $value);
        }
    }

    /**
     * @param callable $callback
     * @return string
     */
    protected function buildCallable($callback)
    {
        $condition = $this->builderFactory->create('conditionGroup', $this->builderFactory);
        call_user_func($callback, $condition);

        return $condition->build();
    }

    /**
     * @param BuilderInterface|string $condition
     * @return string
     */
    protected function buildRaw($condition)
    {
        return $condition instanceof BuilderInterface ? $condition->build() : $condition;
    }

    /**
     * @param BuilderInterface|string $query
     * @return string
     */
    protected function buildExists($query)
    {
        // Build the condition manually, because __toString method must not throw exceptions
        $query = $query instanceof BuilderInterface ? $query->build() : $query;

        return "EXISTS ($query)";
    }

    /**
     * @param BuilderInterface|string $query
     * @return string
     */
    protected function buildNotExists($query)
    {
        // Build the condition manually, because __toString method must not throw exceptions
        $query = $query instanceof BuilderInterface ? $query->build() : $query;

        return "NOT EXISTS ($query)";
    }

    /**
     * @param string $column
     * @return string
     */
    protected function buildIsNull($column)
    {
        return "$column IS NULL";
    }

    /**
     * @param string $column
     * @return string
     */
    protected function buildIsNotNull($column)
    {
        return "$column IS NOT NULL";
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return string
     */
    protected function buildBetween($column, $lowest, $highest)
    {
        $lowest = $this->escapeValue($lowest);
        $highest = $this->escapeValue($highest);

        return "$column BETWEEN $lowest AND $highest";
    }

    /**
     * @param string $column
     * @param mixed $lowest
     * @param mixed $highest
     * @return string
     */
    protected function buildNotBetween($column, $lowest, $highest)
    {
        $lowest = $this->escapeValue($lowest);
        $highest = $this->escapeValue($highest);

        return "$column NOT BETWEEN $lowest AND $highest";
    }

    /**
     * @param string $column
     * @param array $values
     * @return string
     */
    protected function buildIn($column, array $values)
    {
        $values = implode(',', $this->escapeValues($values));

        return "$column IN ($values)";
    }

    /**
     * @param string $column
     * @param array $values
     * @return string
     */
    protected function buildNotIn($column, array $values)
    {
        $values = implode(',', $this->escapeValues($values));

        return "$column NOT IN ($values)";
    }

    /**
     * @param string $column
     * @param array $values
     * @return string
     */
    protected function buildInSet($column, array $values)
    {
        $values = implode(',', $this->escapeValues($values));

        return "FIND_IN_SET($column, $values)";
    }

    /**
     * @param string $column
     * @param array $values
     * @return string
     */
    protected function buildNotInSet($column, array $values)
    {
        $values = implode(',', $this->escapeValues($values));

        return "NOT FIND_IN_SET($column, $values)";
    }

    /**
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return string
     */
    protected function buildDefault($column, $operator, $value)
    {
        $value = $this->escapeValue($value);

        return "$column $operator $value";
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
            if (is_string($value)) {
                $values[$key] = $this->connection->quote($value);
            }
        }

        return $values;
    }
}
