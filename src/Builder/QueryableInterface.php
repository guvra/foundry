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
interface QueryableInterface
{
    /**
     * Execute a SQL statement, returning a result set as a PDOStatement object.
     *
     * @param array $bind
     * @return \Guvra\StatementInterface
     * @throws \PDOException
     */
    public function query(array $bind = []);

    /**
     * Execute a SQL statement and return the number of affected rows.
     *
     * @return int|false
     * @throws \PDOException
     */
    public function exec();

    /**
     * Prepare a statement for execution and return a statement object.
     *
     * @return \Guvra\StatementInterface
     * @throws \PDOException
     */
    public function prepare();
}
