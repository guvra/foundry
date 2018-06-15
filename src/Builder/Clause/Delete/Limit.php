<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Clause\Delete;

use Foundry\Builder\Builder;

/**
 * LIMIT builder.
 */
class Limit extends Builder
{
    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * Set the limit.
     *
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        if ($this->limit === 0) {
            return '';
        }

        return 'LIMIT ' . $this->limit;
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        $this->limit = 0;

        return $this;
    }
}
