<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\AbstractBuilder;
use Guvra\ConnectionInterface;

/**
 * Group clause.
 */
class Group extends AbstractBuilder
{
    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @param ConnectionInterface $connection
     * @param array $columns
     */
    public function __construct(ConnectionInterface $connection, array $columns = [])
    {
        parent::__construct($connection);
        $this->columns = $columns;
    }

    /**
     * Add a column to the GROUP BY clause.
     *
     * @param string $column
     * @return $this
     */
    public function addColumn($column)
    {
        $this->addColumns([$column]);

        return $this;
    }

    /**
     * Add columns to the GROUP BY clause.
     *
     * @param string|array $columns
     * @return $this
     */
    public function addColumns($columns)
    {
        if (!is_array($columns)) {
            $columns = (array) $columns;
        }

        $this->columns = array_unique(array_merge($this->columns, $columns));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        if (empty($this->columns)) {
            return '';
        }

        $columns = implode(', ', $this->columns);

        return 'GROUP BY ' . $columns;
    }
}
