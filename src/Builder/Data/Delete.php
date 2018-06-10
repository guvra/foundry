<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Data;

use Guvra\Builder\QueryableBuilder;
use Guvra\Builder\Traits\HasJoin;
use Guvra\Builder\Traits\HasWhere;
use Guvra\ConnectionInterface;

/**
 * Delete builder.
 */
class Delete extends QueryableBuilder
{
    use HasJoin;
    use HasWhere;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * Set the FROM clause.
     *
     * @param string $table
     * @return $this
     */
    public function from(string $table)
    {
        $this->table = $table;
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
        return 'DELETE'
            . $this->buildTable()
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
        if (empty($this->table)) {
            return '';
        }

        return " FROM {$this->table}";
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
