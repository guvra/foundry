<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Statement;

use Guvra\Builder\Builder;
use Guvra\Builder\Traits\HasJoin;
use Guvra\Builder\Traits\HasWhere;

/**
 * Update builder.
 */
class Update extends Builder
{
    use HasJoin;
    use HasWhere;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var string
     */
    protected $alias = '';

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * Set the table to update.
     *
     * @param string $table
     * @param string $alias
     * @return $this
     */
    public function table(string $table, string $alias = '')
    {
        $this->table = $table;
        $this->alias = $alias;
        $this->compiled = null;

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
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a limit clause to the query.
     *
     * @param int $max
     * @return $this
     */
    public function limit(int $max)
    {
        $this->limit = $max;
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        return 'UPDATE'
            . $this->buildTable()
            . $this->buildValues()
            . $this->buildJoins()
            . $this->buildWhere()
            . $this->buildLimit();
    }

    /**
     * Build the table name.
     *
     * @return string
     */
    protected function buildTable()
    {
        if (!$this->table) {
            return '';
        }

        return $this->alias ? " {$this->table} AS {$this->alias}" : " {$this->table}";
    }

    /**
     * Build the values.
     *
     * @return string
     */
    protected function buildValues()
    {
        if (empty($this->values)) {
            return '';
        }

        $values = [];

        foreach ($this->values as $column => $value) {
            if (is_string($value)) {
                $value = $this->connection->quote($value);
            }
            $values[$column] = "$column = $value";
        }

        return ' SET ' . implode(', ', $values);
    }

    /**
     * Build the limit clause.
     *
     * @return string
     */
    protected function buildLimit()
    {
        return $this->limit > 0 ? " LIMIT {$this->limit}" : '';
    }
}
