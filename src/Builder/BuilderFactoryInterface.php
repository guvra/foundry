<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

/**
 * Query builder factory interface.
 */
interface BuilderFactoryInterface
{
    /**
     * Create a new query builder.
     *
     * @param string $type
     * @param mixed ...$args
     * @return BuilderInterface
     * @throws \UnexpectedValueException
     */
    public function create(string $type, ...$args);

    /**
     * Add a query builder type.
     *
     * @param string $type
     * @param string $className
     * @return $this
     */
    public function addBuilder(string $type, string $className);

    /**
     * Remove a query builder type.
     *
     * @param string $type
     * @return $this
     */
    public function removeBuilder(string $type);
}
