<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder;

/**
 * Statement builder interface.
 */
interface StatementBuilderInterface extends BuilderInterface
{
    /**
     * Get a part of the SQL query.
     *
     * @param string $name
     * @return BuilderInterface
     * @throws \UnexpectedValueException
     */
    public function getPart(string $name);

    /**
     * Reset a part of the query (or the whole query).
     *
     * @param string|null $part
     * @return $this
     * @throws \UnexpectedValueException
     */
    public function reset(string $part = null);
}
