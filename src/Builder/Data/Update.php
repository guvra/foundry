<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Data;

use Guvra\Builder\BuilderFactoryInterface;
use Guvra\Builder\QueryableBuilder;
use Guvra\ConnectionInterface;

/**
 * Update builder.
 */
class Update extends QueryableBuilder
{
    use \Guvra\Builder\Traits\WhereTrait;
    use \Guvra\Builder\Traits\JoinTrait;

    /**
     * @var string
     */
    protected $table = '';

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

    /**
     * Add a limit clause to the query.
     *
     * @param int $max
     * @return $this
     */
    public function limit(int $max)
    {
        $this->limit = $max;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
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
        return !empty($this->table) ? " {$this->table}" : '';
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
