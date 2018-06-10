<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Data\Select;

use Guvra\Builder\Builder;
use Guvra\ConnectionInterface;

/**
 * Order builder.
 */
class OrderGroup extends Builder
{
    /**
     * @var array
     */
    protected $orders = [];

    /**
     * @param ConnectionInterface $connection
     * @param array $orders
     */
    public function __construct(ConnectionInterface $connection, array $orders = [])
    {
        parent::__construct($connection);
        $this->orders = $orders;
    }

    /**
     * Add columns to the ORDER clause.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function addOrder($column, $direction = 'asc')
    {
        $this->orders[] = [$column, $direction];
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        if (empty($this->orders)) {
            return '';
        }

        $parts = [];

        foreach ($this->orders as $order) {
            $column = $order[0];
            $direction = $order[1];

            if (empty($column)) {
                continue;
            }

            $parts[] = $direction !== '' ? "$column $direction" : $column;
        }

        return 'ORDER BY ' . implode(', ', $parts);
    }
}
