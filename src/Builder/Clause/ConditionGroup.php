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
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where($column, $operator = null, $value = null)
    {
        $condition = $this->builderFactory->create('condition', $column, $operator, $value);

        return $this->addCondition($condition);
    }

    /**
     * @param mixed $column
     * @param mixed|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        $condition = $this->builderFactory->create('condition', $column, $operator, $value);

        return $this->addOrCondition($condition);
    }

    /**
     * @param BuilderInterface $condition
     * @return $this
     */
    public function addCondition(BuilderInterface $condition)
    {
        $this->conditions[] = ['AND', $condition];
        $this->compiled = null;

        return $this;
    }

    /**
     * @param BuilderInterface $condition
     * @return $this
     */
    public function addOrCondition(BuilderInterface $condition)
    {
        $this->conditions[] = ['OR', $condition];
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

        foreach ($this->conditions as $conditionData) {
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
