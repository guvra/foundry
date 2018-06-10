<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra;

use Guvra\Builder\BuilderInterface;
use Guvra\StatementInterface;

/**
 * Connection interface.
 */
interface ConnectionInterface
{
    /**
     * Execute a SQL statement, returning a result set as a PDOStatement object.
     *
     * @param BuilderInterface|string $query
     * @param array $bind
     * @return StatementInterface
     * @throws \PDOException
     */
    public function query($query);

    /**
     * Execute a SQL statement and return the number of affected rows.
     *
     * @param BuilderInterface|string $query
     * @return int|false
     * @throws \PDOException
     */
    public function exec($query);

    /**
     * Prepare a statement for execution and return a statement object.
     *
     * @param BuilderInterface|string $query
     * @return StatementInterface
     * @throws \PDOException
     */
    public function prepare($query);

    /**
     * Quote a string for use in a query.
     *
     * @param string $value
     * @return string|false
     */
    public function quote($value);

    /**
     * Initiate a transaction.
     *
     * Returns false on failure.
     * Throws an exception if there is already an active transaction,
     * or if transactions are not supported.
     *
     * @return bool
     * @throws \PDOException
     */
    public function beginTransaction();

    /**
     * Commit the current transaction.
     *
     * Returns false on failure.
     * Throws an exception when there is no active transaction.
     *
     * @return bool
     * @throws \PDOException
     */
    public function commitTransaction();

    /**
     * Roll back the current transaction.
     *
     * Returns false on failure.
     * Throws an exception when there is no active transaction.
     *
     * @return bool
     * @throws \PDOException
     */
    public function rollbackTransaction();

    /**
     * Get the ID of the last inserted row (does not work with UPDATE),
     * or the last value from a sequence object, depending on the underlying driver.
     *
     * @param string|null $name
     * @return string
     */
    public function lastInsertId($name = null);

    /**
     * Create a select query.
     *
     * @return BuilderInterface
     */
    public function select();

    /**
     * Create an insert query.
     *
     * @return BuilderInterface
     */
    public function insert();

    /**
     * Create an update query.
     *
     * @return BuilderInterface
     */
    public function update();

    /**
     * Create a delete query.
     *
     * @return BuilderInterface
     */
    public function delete();

    /**
     * Get the numbers of rows of a specific table.
     *
     * @param string $tableName
     * @param callable|null $callback
     * @return int
     * @throws \PDOException
     */
    public function getRowCount($tableName, $callback = null);

    /**
     * Get the name of the database driver.
     *
     * @return string
     */
    public function getDriver();
}
