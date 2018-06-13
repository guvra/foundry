<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
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
    public function __construct(string $className = Statement::class)
    {
        $this->className = $className;
    }

    /**
     * Create a new statement.
     *
     * @param \PDOStatement $pdoStatement
     * @return StatementInterface
     */
     public function create(\PDOStatement $pdoStatement)
    {
        return new $this->className($pdoStatement);
    }
}
