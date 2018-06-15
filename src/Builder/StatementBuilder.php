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
 * Abstract statement builder.
 */
abstract class StatementBuilder extends Builder implements StatementBuilderInterface
{
    /**
     * @var string
     */
    protected $statementName = '';

    /**
     * @var string[]
     */
    protected $parts = [];

    /**
     * @var string[]
     */
    protected $resolvedParts = [];

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        parent::__construct($connection);
        $this->initialize();
    }

    /**
     * Initialize the builder.
     */
    abstract protected function initialize();

    /**
     * {@inheritdoc}
     */
    public function getPart(string $name)
    {
        if (!array_key_exists($name, $this->parts)) {
            throw new \UnexpectedValueException(sprintf('The part "%s" does not exist.', $name));
        }

        if (!array_key_exists($name, $this->resolvedParts)) {
            $type = $this->parts[$name];
            $this->resolvedParts[$name] = $this->builderFactory->create($type);
        }

        return $this->resolvedParts[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function reset(string $part = null)
    {
        if ($part) {
            $this->getPart($part)->reset();
        } else {
            $this->decompile();
        }

        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        $result = '';

        foreach ($this->parts as $part => $builder) {
            $partResult = $this->getPart($part)->toString();

            if ($partResult !== '') {
                $result .= ' ' . $partResult;
            }
        }

        return $result !== '' ? $this->statementName . $result : '';
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        foreach ($this->parts as $part => $builder) {
            $this->getPart($part)->reset();
        }
    }
}
