<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Clause;

use Foundry\Builder\Builder;
use Foundry\Builder\Condition;

/**
 * JOIN builder.
 */
class Join extends Builder
{
    const TYPE_INNER = 'inner';
    const TYPE_LEFT = 'left';
    const TYPE_RIGHT = 'right';
    const TYPE_CROSS = 'cross';
    const TYPE_NATURAL = 'natural';

    /**
     * @var array
     */
    protected $joins = [];

    /**
     * Add a join to the group.
     *
     * @param string $type
     * @param string|array $table
     * @param mixed ...$args
     * @return $this
     */
    public function addJoin(string $type, $table, ...$args)
    {
        $condition = null;

        if (!empty($args)) {
            $column = $args[0];
            $operator = isset($args[1]) ? $args[1] : null;
            $value = isset($args[2]) ? $args[2] : null;
            $condition = $this->builderFactory->create('condition', $column, $operator, $value);
        }

        $this->joins[] = ['type' => $type, 'table' => $table, 'condition' => $condition];
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        $result = '';
        $first = true;

        foreach ($this->joins as $join) {
            $joinResult = $this->buildClause($join['type']);
            $joinResult .= $this->buildTable($join['table']);
            $joinResult .= $this->buildCondition($join['condition']);

            $result .= $first ? $joinResult : ' ' . $joinResult;
            $first = false;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        unset($this->joins);
        $this->joins = [];
    }

    /**
     * Build the clause name.
     *
     * @param string $type
     * @return string
     */
    protected function buildClause(string $type)
    {
        switch ($type) {
            case self::TYPE_LEFT:
                return 'LEFT JOIN';

            case self::TYPE_RIGHT:
                return 'RIGHT JOIN';

            case self::TYPE_CROSS:
                return 'CROSS JOIN';

            case self::TYPE_NATURAL:
                return 'NATURAL JOIN';

            default:
                return 'JOIN';
        }
    }

    /**
     * Build the table declaration.
     *
     * @param string|array $table
     * @return string
     */
    protected function buildTable($table)
    {
        $result = '';
        $alias = '';

        // Build the table
        if (is_array($table)) {
            $aliasCandidate = key($table);
            if (is_string($aliasCandidate) && $aliasCandidate !== '') {
                $alias = $aliasCandidate;
            }
            $table = $table[$alias];
        }

        if ((string) $table !== '') {
            $result = $alias !== '' ? ' ' . $table . ' AS ' . $alias : ' ' . $table;
        }

        return $result;
    }

    /**
     * Build the condition.
     *
     * @param Condition|null $condition
     * @return string
     */
    protected function buildCondition(?Condition $condition)
    {
        $result = '';

        if ($condition) {
            $result = $condition->toString();
            if ($result !== '') {
                $result = ' ON ' . $result;
            }
        }

        return $result;
    }
}
