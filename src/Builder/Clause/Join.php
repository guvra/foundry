<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\AbstractBuilder;
use Guvra\Builder\BuilderFactoryInterface;
use Guvra\Builder\Expression;
use Guvra\ConnectionInterface;

/**
 * Join clause.
 * TODO: joins array, use compact function to add values to the array
 *       foreach on the array in build method
 */
class Join extends AbstractBuilder
{
    /**
     * @var BuilderFactoryInterface
     */
    protected $builderFactory;

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var string
     */
    protected $alias = '';

    /**
     * @var \Guvra\Builder\Condition\ConditionGroup|null
     */
    protected $condition;

    /**
     * @param ConnectionInterface $connection
     * @param BuilderFactoryInterface $builderFactory
     * @param array $joins
     */
    public function __construct(
        ConnectionInterface $connection,
        BuilderFactoryInterface $builderFactory
    ) {
        parent::__construct($connection);
        $this->builderFactory = $builderFactory;
    }

    public function join($type, $table, ...$args)
    {
        $args = func_get_args();

        // Join type
        $this->type = $type;

        // Join table/alias
        if (is_array($table)) {
            $this->alias = $table[0];
            $this->table = $table[1];
        } else {
            $this->table = $table;
        }

        // Join condition
        if (isset($args[2])) {
            $column = $args[2];
            $operator = isset($args[3]) ? $args[3] : null;
            $value = isset($args[4]) ? new Expression($args[4]) : null;

            $this->condition = $this->builderFactory->create('conditionGroup', $this->builderFactory);
            $this->condition->where($column, $operator, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $clause = $this->getClauseName($this->type);
        $result = "$clause $this->table";

        if ($this->alias) {
            $result .= " AS $this->alias";
        }

        if ($this->condition) {
            $result .= ' ON ' . $this->condition->build();
        }

        return $result;
    }

    /**
     * Get the clause name by type.
     *
     * @param string $type
     * @return string
     */
    protected function getClauseName($type)
    {
        switch ($type) {
            case 'left':
                return 'LEFT JOIN';

            case 'right':
                return 'RIGHT JOIN';

            case 'cross':
                return 'CROSS JOIN';

            default:
                return 'JOIN';
        }
    }
}
