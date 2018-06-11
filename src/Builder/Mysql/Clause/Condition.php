<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Mysql\Clause;

use Guvra\Builder\Clause\Condition as BaseCondition;

/**
 * Condition builder for MySQL.
 */
class Condition extends BaseCondition
{
    /**
    * {@inheritdoc}
     */
    protected function buildCondition($column, string $operator, $value)
    {
        switch ($operator) {
            case 'in set':
                return $this->buildInSet($column, $value);

            case 'not in set':
                return $this->buildNotInSet($column, $value);

            default:
                return parent::buildCondition($column, $operator, $value);
        }
    }

    /**
     * @param string $column
     * @param array|string|null $values
     * @return string
     */
    protected function buildInSet($column, $values)
    {
        if (is_array($values)) {
            $values = implode(',', $values);
        }

        $value = $this->escapeValue($values);

        return "FIND_IN_SET($column, $value)";
    }

    /**
     * @param string $column
     * @param array|string|null $values
     * @return string
     */
    protected function buildNotInSet($column, $values)
    {
        if (is_array($values)) {
            $values = implode(',', $values);
        }

        $value = $this->escapeValue($values);

        return "NOT FIND_IN_SET($column, $value)";
    }
}
