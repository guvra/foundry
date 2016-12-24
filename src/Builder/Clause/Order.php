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
 * Order clause.
 */
class Order extends AbstractBuilder
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
    public function addOrder($column, $direction = 'ASC')
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

            $parts[] = "$column $direction";
        }

        return 'ORDER BY ' . implode(', ', $parts);
    }
}
