<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
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
     * @param array $args
     * @return BuilderInterface
     * @throws \UnexpectedValueException
     */
    public function create($type, ...$args);

    /**
     * Add a query builder type.
     *
     * @param string $type
     * @param string $className
     * @return $this
     */
    public function addBuilder($type, $className);

    /**
     * Remove a query builder type.
     *
     * @param string $type
     * @return $this
     */
    public function removeBuilder($type);
}
