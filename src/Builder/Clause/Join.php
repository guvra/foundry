<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\Builder;
use Guvra\Builder\BuilderFactoryInterface;
use Guvra\Builder\Expression;
use Guvra\ConnectionInterface;

/**
 * Join builder.
 */
class Join extends Builder
{
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
     * @var Condition|null
     */
    protected $condition;

    /**
     * @param ConnectionInterface $connection
     * @param string $type
     * @param string|array $table
     * @param mixed|null $value
     */
    public function __construct(ConnectionInterface $connection, string $type, $table, Condition $condition = null)
    {
        parent::__construct($connection);
        $this->type = $type;
        $this->condition = $condition;
        $this->setTable($table);
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
            $conditionResult =  $this->condition->build();
            if ($conditionResult !== '') {
                $result .= ' ON ' . $conditionResult;
            }

        }

        return $result;
    }

    /**
     * @param string|array $table
     */
    protected function setTable($table)
    {
        if (is_array($table)) {
            $tableIndex = key($table);
            if (!is_numeric($tableIndex)) {
                $this->alias = key($table);
            }
            $this->table = $table[$tableIndex];
        } else {
            $this->table = $table;
        }
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

            case 'natural':
                return 'NATURAL JOIN';

            default:
                return 'JOIN';
        }
    }
}
