<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra;

/**
 * Connection bag.
 */
class ConnectionBag
{
    /**
     * Available connections.
     *
     * @var ConnectionInterface[]
     */
    protected $connections = [];

    /**
     * Get a database connection.
     *
     * @param string $name
     * @return ConnectionInterface|null
     */
    public function getConnection($name = 'default')
    {
        return array_key_exists($name, $this->connections) ? $this->connections[$name] : null;
    }

    /**
     * Add a database connection
     *
     * @param ConnectionInterface $connection
     * @param string $name
     * @return $this
     */
    public function addConnection(ConnectionInterface $connection, $name = 'default')
    {
        $this->connections[$name] = $connection;

        return $this;
    }
}
