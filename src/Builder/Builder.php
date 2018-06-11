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
     * @var BuilderFactoryInterface
     */
    protected $builderFactory;

    /**
     * @var string|null
     */
    protected $compiled;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->builderFactory = $connection->getBuilderFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        if ($this->compiled === null) {
            $this->compiled = $this->compile();
        }

        return $this->compiled;
    }

    /**
     * Get the SQL query string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
