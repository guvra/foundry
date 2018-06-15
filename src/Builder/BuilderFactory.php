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
            'select' => 'Foundry\Builder\{driver\}Statement\Select',
            'insert' => 'Foundry\Builder\{driver\}Statement\Insert',
            'update' => 'Foundry\Builder\{driver\}Statement\Update',
            'delete' => 'Foundry\Builder\{driver\}Statement\Delete',

            // Clause builders
            'condition' => 'Foundry\Builder\{driver\}Condition',
            'conditionGroup' => 'Foundry\Builder\{driver\}ConditionGroup',

            'select/columns' => 'Foundry\Builder\{driver\}Clause\Select\Columns',
            'select/distinct' => 'Foundry\Builder\{driver\}Clause\Select\Distinct',
            'select/from' => 'Foundry\Builder\{driver\}Clause\Select\From',
            'select/join' => 'Foundry\Builder\{driver\}Clause\Join',
            'select/where' => 'Foundry\Builder\{driver\}Clause\Where',
            'select/group' => 'Foundry\Builder\{driver\}Clause\Select\Group',
            'select/having' => 'Foundry\Builder\{driver\}Clause\Having',
            'select/limit' => 'Foundry\Builder\{driver\}Clause\Select\Limit',
            'select/order' => 'Foundry\Builder\{driver\}Clause\Select\Order',
            'select/union' => 'Foundry\Builder\{driver\}Clause\Select\Union',

            'insert/ignore' => 'Foundry\Builder\{driver\}Clause\Insert\Ignore',
            'insert/table' => 'Foundry\Builder\{driver\}Clause\Insert\Table',
            'insert/columns' => 'Foundry\Builder\{driver\}Clause\Insert\Columns',
            'insert/values' => 'Foundry\Builder\{driver\}Clause\Insert\Values',

            'update/table' => 'Foundry\Builder\{driver\}Clause\Update\Table',
            'update/join' => 'Foundry\Builder\{driver\}Clause\Join',
            'update/values' => 'Foundry\Builder\{driver\}Clause\Update\Values',
            'update/where' => 'Foundry\Builder\{driver\}Clause\Where',
            'update/limit' => 'Foundry\Builder\{driver\}Clause\Update\Limit',

            'delete/table' => 'Foundry\Builder\{driver\}Clause\Delete\Table',
            'delete/join' => 'Foundry\Builder\{driver\}Clause\Join',
            'delete/where' => 'Foundry\Builder\{driver\}Clause\Where',
            'delete/limit' => 'Foundry\Builder\{driver\}Clause\Delete\Limit',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $type, ...$args)
    {
        if (!$this->isResolved($type)) {
            if (!$this->hasBuilder($type)) {
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
    public function addBuilder(string $type, string $className)
    {
        $this->builders[$type] = $className;
        unset($this->resolvedBuilders[$type]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeBuilder(string $type)
    {
        unset($this->builders[$type]);
        unset($this->resolvedBuilders[$type]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasBuilder(string $type)
    {
        return array_key_exists($type, $this->builders);
    }

    /**
     * Check whether the builder of the specified type was resolved.
     *
     * @param string $type
     * @return bool
     */
    protected function isResolved(string $type)
    {
        return array_key_exists($type, $this->resolvedBuilders);
    }
}
