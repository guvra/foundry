<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder;

use Foundry\ConnectionInterface;

/**
 * Abstract builder.
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
     * Compiles the query.
     *
     * @return string
     */
    abstract protected function compile();

    /**
     * Decompiles the query.
     *
     * @return string
     */
    abstract protected function decompile();

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
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->decompile();
        $this->compiled = null;

        return $this;
    }

    /**
     * Parse a value that can be either a string, a BuilderInterface implementation, or a callable.
     *
     * @param mixed $value
     * @param bool $enclose
     * @return mixed
     */
    protected function parseSubQuery($value, bool $enclose = true)
    {
        if (is_object($value) && $value instanceof \Closure) {
            $subQuery = $this->builderFactory->create('select');
            call_user_func($value, $subQuery);
            $value = $subQuery->toString();
            if ($enclose) {
                $value = '(' . $value . ')';
            }
        } elseif (is_object($value) && $value instanceof BuilderInterface) {
            $value = $value->toString();
            if ($enclose) {
                $value = '(' . $value . ')';
            }
        }

        return $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
