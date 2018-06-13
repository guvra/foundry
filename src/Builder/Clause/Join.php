<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\Builder;
use Guvra\ConnectionInterface;

/**
 * Join builder.
 */
class Join extends Builder
{
    const TYPE_INNER = 'inner';
    const TYPE_LEFT = 'left';
    const TYPE_RIGHT = 'right';
    const TYPE_CROSS = 'cross';
    const TYPE_NATURAL = 'natural';

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
            case self::TYPE_LEFT:
                return 'LEFT JOIN';

            case self::TYPE_RIGHT:
                return 'RIGHT JOIN';

            case self::TYPE_CROSS:
                return 'CROSS JOIN';

            case self::TYPE_NATURAL:
                return 'NATURAL JOIN';

            default:
                return 'JOIN';
        }
    }
}
