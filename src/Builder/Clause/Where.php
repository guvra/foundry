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
use Guvra\Builder\Clause\Where\ConditionGroup;
use Guvra\ConnectionInterface;

/**
 * Where clause.
 */
class Where extends ConditionGroup
{
    /**
     * @param ConnectionInterface $connection
     * @param BuilderFactoryInterface $builderFactory
     */
    public function __construct(ConnectionInterface $connection, BuilderFactoryInterface $builderFactory)
    {
        parent::__construct($connection, $builderFactory);
    }

    public function build()
    {
        $result = parent::build();

        return $result !== '' ? "WHERE $result" : '';
    }
}
