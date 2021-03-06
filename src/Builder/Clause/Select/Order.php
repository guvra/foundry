<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Clause\Select;

use Foundry\Builder\Builder;

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
     * Add orders.
     *
     * @param string[] $orders
     * @return $this
     */
    public function addOrders(array $orders)
    {
        $this->orders = array_merge($this->orders, $orders);
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
