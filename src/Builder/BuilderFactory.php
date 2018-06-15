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
            'select' => 'Guvra\Builder\{driver\}Statement\Select',
            'insert' => 'Guvra\Builder\{driver\}Statement\Insert',
            'update' => 'Guvra\Builder\{driver\}Statement\Update',
            'delete' => 'Guvra\Builder\{driver\}Statement\Delete',

            // Clause builders
            'condition' => 'Guvra\Builder\{driver\}Condition',
            'conditionGroup' => 'Guvra\Builder\{driver\}ConditionGroup',

            'select/columns' => 'Guvra\Builder\{driver\}Clause\Select\Columns',
            'select/distinct' => 'Guvra\Builder\{driver\}Clause\Select\Distinct',
            'select/from' => 'Guvra\Builder\{driver\}Clause\Select\From',
            'select/join' => 'Guvra\Builder\{driver\}Clause\Join',
            'select/where' => 'Guvra\Builder\{driver\}Clause\Where',
            'select/group' => 'Guvra\Builder\{driver\}Clause\Select\Group',
            'select/having' => 'Guvra\Builder\{driver\}Clause\Having',
            'select/limit' => 'Guvra\Builder\{driver\}Clause\Select\Limit',
            'select/order' => 'Guvra\Builder\{driver\}Clause\Select\Order',
            'select/union' => 'Guvra\Builder\{driver\}Clause\Select\Union',

            'insert/ignore' => 'Guvra\Builder\{driver\}Clause\Insert\Ignore',
            'insert/table' => 'Guvra\Builder\{driver\}Clause\Insert\Table',
            'insert/columns' => 'Guvra\Builder\{driver\}Clause\Insert\Columns',
            'insert/values' => 'Guvra\Builder\{driver\}Clause\Insert\Values',

            'update/table' => 'Guvra\Builder\{driver\}Clause\Update\Table',
            'update/join' => 'Guvra\Builder\{driver\}Clause\Join',
            'update/values' => 'Guvra\Builder\{driver\}Clause\Update\Values',
            'update/where' => 'Guvra\Builder\{driver\}Clause\Where',
            'update/limit' => 'Guvra\Builder\{driver\}Clause\Update\Limit',

            'delete/table' => 'Guvra\Builder\{driver\}Clause\Delete\Table',
            'delete/join' => 'Guvra\Builder\{driver\}Clause\Join',
            'delete/where' => 'Guvra\Builder\{driver\}Clause\Where',
            'delete/limit' => 'Guvra\Builder\{driver\}Clause\Delete\Limit',
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
