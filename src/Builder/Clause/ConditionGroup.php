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

        return $this->addCondition($condition);
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

        return $this->addOrCondition($condition);
    }

    /**
     * @param Condition $condition
     * @return $this
     */
    public function addCondition(Condition $condition)
    {
        $this->conditions[] = ['and', $condition];
        $this->compiled = null;

        return $this;
    }

    /**
     * @param Condition $condition
     * @return $this
     */
    public function addOrCondition(Condition $condition)
    {
        $this->conditions[] = ['or', $condition];
        $this->compiled = null;

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
    public function compile()
    {
        $first = true;
        $result = '';

        foreach ($this as $conditionData) {
            $type = $conditionData[0];
            $compiledCondition = $conditionData[1]->toString();

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
