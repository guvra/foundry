<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause\Where;

use Guvra\Builder\AbstractBuilder;
use Guvra\Builder\BuilderFactory;
use Guvra\Builder\BuilderFactoryInterface;
use Guvra\Builder\BuilderInterface;
use Guvra\ConnectionInterface;

/**
 * Condition group builder.
 */
class ConditionGroup extends AbstractBuilder implements \IteratorAggregate, \Countable
{
    /**
     * @var BuilderFactoryInterface
     */
    protected $builderFactory;

    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @param ConnectionInterface $connection
     * @param BuilderFactoryInterface|null $builderFactory
     */
    public function __construct(ConnectionInterface $connection, BuilderFactoryInterface $builderFactory = null)
    {
        parent::__construct($connection);
        $this->builderFactory = $builderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->conditions);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->conditions);
    }

    /**
     * @param BuilderInterface|string|callable $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where($column, $operator = null, $value = null)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, $operator, $value);
        $this->addCondition($condition);

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
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, $operator, $value);
        $this->addOrCondition($condition);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function whereExists($query)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $query, 'exists');
        $this->addCondition($condition);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orWhereExists($query)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $query, 'exists');
        $this->addOrCondition($condition, 'or');

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function whereNotExists($query)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $query, 'not exists');
        $this->addCondition($condition);

        return $this;
    }

    /**
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function orWhereNotExists($query)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $query, 'not exists');
        $this->addOrCondition($condition, 'or');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function whereIsNull($column)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'null');
        $this->addCondition($condition);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orWhereIsNull($column)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'null');
        $this->addOrCondition($condition, 'or');

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function whereNotNull($column)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'not null');
        $this->addCondition($condition);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orWhereNotNull($column)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'not null');
        $this->addOrCondition($condition, 'or');

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
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'between', [$lowest, $highest]);
        $this->addCondition($condition);

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
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'between', [$lowest, $highest]);
        $this->addOrCondition($condition, 'or');

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
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'not between', [$lowest, $highest]);
        $this->addCondition($condition);

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
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'not between', [$lowest, $highest]);
        $this->addOrCondition($condition, 'or');

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereIn($column, array $values)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'in', $values);
        $this->addCondition($condition);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereIn($column, array $values)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'in', $values);
        $this->addOrCondition($condition, 'or');

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereNotIn($column, array $values)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'not in', $values);
        $this->addCondition($condition);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereNotIn($column, array $values)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'not in', $values);
        $this->addOrCondition($condition, 'or');

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereInSet($column, array $values)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'finset', $values);
        $this->addCondition($condition);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereInSet($column, array $values)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'finset', $values);
        $this->addOrCondition($condition, 'or');

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereNotInSet($column, array $values)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'not finset', $values);
        $this->addCondition($condition);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function orWhereNotInSet($column, array $values)
    {
        $condition = $this->builderFactory->create('condition', $this->builderFactory, $column, 'not finset', $values);
        $this->addOrCondition($condition, 'or');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $first = true;
        $result = '';

        foreach ($this as $conditionData) {
            $type = $conditionData[0];
            $condition = $conditionData[1];

            // Build the condition manually, because __toString method must not throw exceptions
            $compiledCondition = $condition->build();

            if (!$first) {
                $result .= " $type ";
            }

            $result .= "($compiledCondition)";

            if ($first) {
                $first = false;
            }
        }

        return $result;
    }

    /**
     * Add a "AND" condition to the group.
     *
     * @param BuilderInterface|string $condition
     * @return $this
     */
    protected function addCondition($condition)
    {
        $this->conditions[] = ['and', $condition];

        return $this;
    }

    /**
     * Add a "OR" condition to the group.
     *
     * @param BuilderInterface|string $condition
     * @return $this
     */
    protected function addOrCondition($condition)
    {
        $this->conditions[] = ['or', $condition];

        return $this;
    }
}
