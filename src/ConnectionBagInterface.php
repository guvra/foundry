<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra;

/**
 * Connection bag interface.
 */
interface ConnectionBagInterface
{
    /**
     * Get a database connection.
     *
     * @param string $name
     * @return ConnectionInterface
     * @throws \UnexpectedValueException
     */
    public function getConnection(string $name = 'default');

    /**
     * Check if the specified connection is defined.
     *
     * @param string $name
     * @return bool
     */
    public function hasConnection(string $name);

    /**
     * Add a database connection
     *
     * @param ConnectionInterface $connection
     * @param string $name
     * @return $this
     */
    public function addConnection(ConnectionInterface $connection, string $name = 'default');

    /**
     * Remove a database connection
     *
     * @param string $name
     * @return $this
     */
    public function removeConnection(string $name);
}
