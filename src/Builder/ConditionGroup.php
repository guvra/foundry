<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

/**
 * Condition group builder.
 */
class ConditionGroup extends Builder
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
     * Add a "AND" condition.
     * @param mixed $column
     * @param string|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function where($column, string $operator = null, $value = null)
    {
        $condition = $this->builderFactory->create('condition', $column, $operator, $value);

        return $this->addCondition('AND', $condition);
    }

    /**
     * Add a "OR" condition.
     *
     * @param mixed $column
     * @param string|null $operator
     * @param mixed|null $value
     * @return $this
     */
    public function orWhere($column, string $operator = null, $value = null)
    {
        $condition = $this->builderFactory->create('condition', $column, $operator, $value);

        return $this->addCondition('OR', $condition);
    }

    /**
     * Add a condition.
     *
     * @param string $type
     * @param BuilderInterface $condition
     * @return $this
     */
    protected function addCondition(string $type, BuilderInterface $condition)
    {
        $this->conditions[] = [$type, $condition];
        $this->compiled = null;

        return $this;
    }

    /**
     * Set whether to enclose the query.
     *
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
    protected function compile()
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
    protected function decompile()
    {
        unset($this->conditions);
        $this->conditions = [];
    }
}
