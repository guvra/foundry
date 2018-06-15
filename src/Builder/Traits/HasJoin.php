<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Traits;

use Foundry\Builder\Clause\Join;

/**
 * Join trait.
 */
trait HasJoin
{
    /**
     * Add an inner join clause to the query.
     *
     * @param string|array $table
     * @param mixed ...$args
     * @return $this
     */
    public function join($table, ...$args)
    {
        $this->getPart('join')->addJoin(Join::TYPE_INNER, $table, ...$args);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a left join clause to the query.
     *
     * @param string|array $table
     * @param mixed ...$args
     * @return $this
     */
    public function joinLeft($table, ...$args)
    {
        $this->getPart('join')->addJoin(Join::TYPE_LEFT, $table, ...$args);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a right join clause to the query.
     *
     * @param string|array $table
     * @param mixed ...$args
     * @return $this
     */
    public function joinRight($table, ...$args)
    {
        $this->getPart('join')->addJoin(Join::TYPE_RIGHT, $table, ...$args);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a cross join clause to the query.
     *
     * @param string|array $table
     * @return $this
     */
    public function joinCross($table)
    {
        $this->getPart('join')->addJoin(Join::TYPE_CROSS, $table);
        $this->compiled = null;

        return $this;
    }

    /**
     * Add a natural join clause to the query.
     *
     * @param string|array $table
     * @return $this
     */
    public function joinNatural($table)
    {
        $this->getPart('join')->addJoin(Join::TYPE_NATURAL, $table);
        $this->compiled = null;

        return $this;
    }
}
