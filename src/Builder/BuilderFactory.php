<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

use Guvra\ConnectionInterface;

/**
 * Query builder factory.
 */
class BuilderFactory implements BuilderFactoryInterface
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var string
     */
    protected $driverNamespace = '';

    /**
     * @var string[]
     */
    protected $builders = [];

    /**
     * @var string[]
     */
    protected $resolvedBuilders = [];

    /**
     * @param ConnectionInterface $connection
     * @param array $builders
     */
    public function __construct(ConnectionInterface $connection, array $builders = [])
    {
        $this->connection = $connection;
        $this->driverNamespace = ucfirst($connection->getDriver());
        $this->builders = $builders + [
            // Query builders
            'select' => 'Guvra\Builder\{driver\}Data\Select',
            'insert' => 'Guvra\Builder\{driver\}Data\Insert',
            'update' => 'Guvra\Builder\{driver\}Data\Update',
            'delete' => 'Guvra\Builder\{driver\}Data\Delete',

            // Clause builders
            'join' => 'Guvra\Builder\{driver\}Clause\Join',
            'where' => 'Guvra\Builder\{driver\}Clause\Where',
            'group' => 'Guvra\Builder\{driver\}Clause\Group',
            'group' => 'Guvra\Builder\{driver\}Clause\Group',
            'having' => 'Guvra\Builder\{driver\}Clause\Having',
            'order' => 'Guvra\Builder\{driver\}Clause\Order',
            'limit' => 'Guvra\Builder\{driver\}Clause\Limit',
            'union' => 'Guvra\Builder\{driver\}Clause\Union',

            // Condition builders
            'condition' => 'Guvra\Builder\{driver\}Clause\Where\Condition',
            'conditionGroup' => 'Guvra\Builder\{driver\}Clause\Where\ConditionGroup',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function create($type, ...$args)
    {
        if (!array_key_exists($type, $this->resolvedBuilders)) {
            if (!array_key_exists($type, $this->builders)) {
                throw new \UnexpectedValueException(
                    sprintf('The query builder type "%s" does not exist.', $type)
                );
            }

            $className = $this->builders[$type];
            $resolvedClassName = str_replace('{driver\}', $this->driverNamespace . '\\', $className);

            if (!class_exists($resolvedClassName)) {
                $resolvedClassName = str_replace('{driver\}', '', $className);
            }

            $this->resolvedBuilders[$type] = $resolvedClassName;
        }

        return new $this->resolvedBuilders[$type]($this->connection, ...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function addBuilder($type, $className)
    {
        $this->builders[$type] = $className;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeBuilder($type)
    {
        unset($this->builders[$type]);

        return $this;
    }
}
