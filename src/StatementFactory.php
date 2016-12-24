<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra;

/**
 * Statement factory.
 */
class StatementFactory
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @param string $className
     */
    public function __construct($className = 'Guvra\Statement')
    {
        $this->className = $className;
    }

    /**
     * Create a new statement.
     *
     * @return StatementInterface
     */
     public function create(\PDOStatement $pdoStatement)
    {
        return new $this->className($pdoStatement);
    }
}
