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
class ConnectionBag implements ConnectionBagInterface
{
    /**
     * Available connections.
     *
     * @var ConnectionInterface[]
     */
    protected $connections = [];

    /**
     * {@inheritdoc}
     */
    public function getConnection(string $name = 'default')
    {
        if (!$this->hasConnection($name)) {
            throw new \UnexpectedValueException(sprintf('The connection "%s" is not defined.', $name));
        }

        return $this->connections[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasConnection(string $name)
    {
        return array_key_exists($name, $this->connections);
    }

    /**
     * {@inheritdoc}
     */
    public function addConnection(ConnectionInterface $connection, string $name = 'default')
    {
        $this->connections[$name] = $connection;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeConnection(string $name)
    {
        unset($this->connections[$name]);

        return $this;
    }
}
