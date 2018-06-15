<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry;

/**
 * Statement interface.
 */
interface StatementInterface
{
    /**
     * Fetch all rows.
     *
     * Returns a multidimensional array, or false on failure.
     * Columns are indexed by name by default.
     *
     * @return array|false
     */
    public function fetchAll();

    /**
     * Fetch a specific column for all rows.
     *
     * Returns a single dimensional array, or false on failure.
     *
     * @param int $columnIndex
     * @return array|false
     */
    public function fetchColumn(int $columnIndex = 0);

    /**
     * Fetch the next row.
     *
     * Returns false if there are no more rows.
     * Columns are indexed by name by default.
     *
     * @return mixed|false
     */
    public function fetchRow();

    /**
     * Fetch a column value from the next row.
     *
     * Returns false if there are no more rows.
     *
     * @param int $columnIndex
     * @return mixed|false
     */
    public function fetchOne(int $columnIndex = 0);

    /**
     * Advance to the next row.
     *
     * Returns false on failure.
     *
     * @return bool
     */
    public function nextRow();

    /**
     * Get the number of rows affected by the last statement.
     *
     * @return int
     */
    public function getRowCount();

    /**
     * Set the fetch mode for this statement.
     *
     * The class name parameter can be specified if using the FETCH_CLASS mode.
     *
     * @param string $fetchMode
     * @param string|null $className
     * @return $this
     */
    public function setFetchMode($fetchMode, $className = null);

    /**
     * Execute the statement.
     *
     * Returns false on failure.
     *
     * @param array $bind
     * @return bool
     */
    public function execute(array $bind = []);
}
