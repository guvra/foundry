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
 * LIMIT builder.
 */
class Limit extends Builder
{
    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @var int
     */
    protected $offset = 0;

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
     * Set the offset.
     *
     * @param int $offset
     * @return $this
     */
    public function setOffset(int $offset)
    {
        $this->offset = $offset;
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        if ($this->limit === 0 && $this->offset === 0) {
            return '';
        }

        $parts = [];

        if ($this->limit > 0) {
            $parts[] = 'LIMIT ' . $this->limit;
        }

        if ($this->offset > 0) {
            $parts[] = 'OFFSET ' . $this->offset;
        }

        return implode(' ', $parts);
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        $this->limit = 0;
        $this->offset = 0;

        return $this;
    }
}
