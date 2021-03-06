<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry;

use Foundry\Builder\BuilderInterface;
use Foundry\Builder\BuilderFactoryInterface;

/**
 * Connection interface.
 */
interface ConnectionInterface
{
    /**
     * Execute a SQL statement and return a statement object.
     *
     * @param BuilderInterface|string $query
     * @param array $bind
     * @return StatementInterface
     * @throws \PDOException
     */
    public function query($query, array $bind = []);

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
     * Create a select query builder.
     *
     * @return BuilderInterface
     */
    public function select();

    /**
     * Create an insert query builder.
     *
     * @return BuilderInterface
     */
    public function insert();

    /**
     * Create an update query builder.
     *
     * @return BuilderInterface
     */
    public function update();

    /**
     * Create a delete query builder.
     *
     * @return BuilderInterface
     */
    public function delete();

    /**
     * Get the ID of the last inserted row (does not work with UPDATE),
     * or the last value from a sequence object, depending on the underlying driver.
     *
     * @param string|null $name
     * @return string
     */
    public function getLastInsertId($name = null);

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

    /**
     * Get the query builder factory.
     *
     * @return BuilderFactoryInterface
     */
    public function getBuilderFactory();
}
