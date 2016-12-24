<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Data;

use Guvra\Builder\BuilderFactoryInterface;
use Guvra\Builder\Condition\ConditionGroup;
use Guvra\Builder\QueryableBuilder;
use Guvra\ConnectionInterface;

/**
 * Update builder.
 */
class Update extends QueryableBuilder
{
    use \Guvra\Builder\Traits\WhereTrait;

    /**
     * The target table.
     *
     * @var string
     */
    protected $table = '';

    /**
     * Columns to update.
     *
     * @var array
     */
    protected $values = [];

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        return 'UPDATE'
               . $this->buildTable($this->table)
               . $this->buildValues($this->values)
               . $this->buildWhere();
    }

    /**
     * Build the values clause.
     *
     * @param array $values
     * @return string
     */
    protected function buildValues(array $values)
    {
        $value = '';

        if (!empty($values)) {
            $value = ' SET ';
            foreach ($values as $column => $v) {
                if (is_string($v)) {
                    $v = $this->connection->quote($v);
                }

                $values[$column] = "$column = $v";
            }
            $value .= implode(', ', $values);
        }

        return $value;
    }

    /**
     * Set the table to update.
     *
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = (string) $table;

        return $this;
    }

    /**
     * Set the values to update.
     *
     * @param array $values
     * @return $this
     */
    public function values(array $values)
    {
        $this->values = $values;

        return $this;
    }
}
