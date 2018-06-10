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
     * Build the SQL query string.
     *
     * @return string
     */
    public function build();
}
