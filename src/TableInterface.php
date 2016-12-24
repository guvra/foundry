<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra;

/**
 * Table interface.
 */
interface TableInterface
{
    /**
     * Set the table name.
     *
     * @param string $value
     * @return $this
     */
    public function setName($value);

    /**
     * Add a new column definition.
     *
     * @param string $name
     * @param array $definition
     * @return $this
     */
    public function addColumn($name, array $definition);

    /**
     * Add a foreign key.
     *
     * @param string $name
     * @param string $sourceColumn
     * @param string $targetTable
     * @param string $targetColumn
     * @return $this
     */
    public function addForeignKey($name, $sourceColumn, $targetTable, $targetColumn);

    /**
     * Add a key.
     *
     * @param string $name
     * @param string|array $columns
     * @param int $type
     * @return $this
     */
    public function addKey($name, $columns, $type);
}
