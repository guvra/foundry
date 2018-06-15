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
 * UNION builder.
 */
class Union extends Builder
{
    /**
     * @var array
     */
    protected $unions = [];

    /**
     * Add an order.
     *
     * @param mixed $query
     * @param bool $all
     * @return $this
     */
    public function addUnion($query, bool $all = false)
    {
        $this->unions[] = ['query' => $query, 'all' => $all];
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        if (empty($this->unions)) {
            return '';
        }

        $parts = [];

        foreach ($this->unions as $union) {
            $query = $this->parseSubQuery($union['query'], false);
            $parts[] = $union['all'] ? "UNION ALL $query" : "UNION $query";
        }

        return implode(' ', $parts);
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        unset($this->unions);
        $this->unions = [];

        return $this;
    }
}
