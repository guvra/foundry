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
 * Condition group builder.
 */
class ConditionGroup extends Builder implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @var bool
     */
    protected $enclose = true;

    /**
     * @param BuilderInterface|string|callable $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where($column, $operator = null, $value = null)
    {
        $condition = $this->connection->getBuilderFactory()->create('condition', $column, $operator, $value);
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
        $condition = $this->connection->getBuilderFactory()->create('condition', $column, $operator, $value);
        $this->addOrCondition($condition);

        return $this;
    }

    /**
     * Add a "AND" condition to the group.
     *
     * @param Condition $condition
     * @return $this
     */
    protected function addCondition(Condition $condition)
    {
        $this->conditions[] = ['and', $condition];

        return $this;
    }

    /**
     * Add a "OR" condition to the group.
     *
     * @param Condition $condition
     * @return $this
     */
    protected function addOrCondition(Condition $condition)
    {
        $this->conditions[] = ['or', $condition];

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setEnclose(bool $value)
    {
        $this->enclose = $value;

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
            $compiledCondition = $conditionData[1]->build();

            if ($compiledCondition === '') {
                continue;
            }

            if (!$first) {
                $result .= " $type ";
            }

            $result .= $this->enclose ? "($compiledCondition)" : $compiledCondition;

            if ($first) {
                $first = false;
            }
        }

        return $result;
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
}
