<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\Builder;
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
     * @param Condition|null $condition
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
    public function compile()
    {
        $clause = $this->getClauseName($this->type);
        $result = "$clause $this->table";

        if ($this->alias) {
            $result .= " AS $this->alias";
        }

        if ($this->condition) {
            $conditionResult = $this->condition->toString();
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
            $alias = key($table);
            if (is_string($alias) && $alias !== '') {
                $this->alias = $alias;
            }
            $this->table = $table[$alias];
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
