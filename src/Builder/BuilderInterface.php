<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder;

/**
 * Builder interface.
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
     * Reset the query.
     *
     * @return $this
     */
    public function reset();
}
