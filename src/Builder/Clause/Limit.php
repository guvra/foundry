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
 * Limit clause.
 */
class Limit extends AbstractBuilder
{
    /**
     * @var int
     */
    protected $max = 0;

    /**
     * @var int
     */
    protected $start = 0;

    /**
     * @param ConnectionInterface $connection
     * @param int $max
     * @param int $start
     */
    public function __construct(ConnectionInterface $connection, $max = 0, $start = 0)
    {
        parent::__construct($connection);
        $this->max = (int) $max;
        $this->start = (int) $start;
    }

    /**
     * Set the maximum number of results.
     *
     * @param int $value
     * @return $this
     */
    public function setMax($value)
    {
        $this->max = (int) $value;

        return $this;
    }

    /**
     * Set the start index.
     *
     * @param int $value
     * @return $this
     */
    public function setStart($value)
    {
        $this->start = (int) $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        if ($this->max == 0 && $this->start == 0) {
            return '';
        }

        return $this->start > 0
            ? "LIMIT {$this->max} OFFSET {$this->start}"
            : "LIMIT {$this->max}";
    }
}
