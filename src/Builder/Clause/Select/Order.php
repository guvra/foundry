<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause\Select;

use Guvra\Builder\Builder;

/**
 * ORDER BY builder.
 */
class Order extends Builder
{
    /**
     * @var string[]
     */
    protected $orders = [];

    /**
     * Set the orders.
     *
     * @param string[] $orders
     * @return $this
     */
    public function setOrders(array $orders)
    {
        $this->orders = $orders;
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        if (empty($this->orders)) {
            return '';
        }

        return 'ORDER BY ' . implode(', ', $this->orders);
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        unset($this->orders);
        $this->orders = [];

        return $this;
    }
}
