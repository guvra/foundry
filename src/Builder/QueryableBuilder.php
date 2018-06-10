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
 * Queryable query builder.
 */
abstract class QueryableBuilder extends Builder implements QueryableInterface
{
    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct($connection);
    }

    /**
     * {@inheritdoc}
     */
    public function query(array $bind = [])
    {
        return $this->connection->query($this, $bind);
    }

    /**
     * {@inheritdoc}
     */
    public function exec()
    {
        return $this->connection->exec($this);
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        return $this->connection->prepare($this);
    }
}
