<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

/**
 * Query builder interface.
 */
interface BuilderInterface
{
    /**
     * Outputs the compiled SQL query.
     *
     * @return string
     */
    public function toString();

    /**
     * Forces a recompilation of the SQL query.
     *
     * @return $this
     */
    public function compile();
}
