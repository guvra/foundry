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
     * @var BuilderFactoryInterface
     */
    protected $builderFactory;

    /**
     * @var array
     */
    protected $parts;

    /**
     * @param ConnectionInterface $connection
     * @param BuilderFactoryInterface|null $builderFactory
     */
    public function __construct(ConnectionInterface $connection, BuilderFactoryInterface $builderFactory = null)
    {
        parent::__construct($connection);
        // A queryable builder needs other builders to build the query (conditions, order, limit...)
        $this->builderFactory = $builderFactory ?: new BuilderFactory($connection);
    }

    public function build()
    {
        $query = '';

        foreach ($this->parts as $part) {
            $compiledPart = $part->build();

            if ($compiledPart) {
                $query .= ' ' . $compiledPart;
            }
        }

        return $query;
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
