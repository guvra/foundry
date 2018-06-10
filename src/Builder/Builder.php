<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

use Guvra\ConnectionInterface;

/**
 * Query builder.
 */
abstract class Builder implements BuilderInterface
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var bool|string
     */
    protected $compiled = false;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get the SQL query string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->compiled !== false ? $this->compiled : $this->build();
    }
}
