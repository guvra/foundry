<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Statement;

use Tests\AbstractTestCase;

/**
 * Test the INSERT query builder.
 */
class InsertTest extends AbstractTestCase
{
    /**
     * Test the INSERT query builder.
     */
    public function testInsert()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->insert()
                ->into('accounts')
                ->columns(['name', 'balance'])
                ->values(['Account 4', 500]);

            $statement = $this->connection->query($query);
            $this->assertEquals(1, $statement->getRowCount());
            $this->assertEquals(3, $this->connection->getRowCount('accounts'));
        });
    }

    /**
     * Test the INSERT query builder with multiple values.
     */
    public function testInsertMultiple()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->insert()
                ->into('accounts')
                ->columns(['name', 'balance'])
                ->values([['Account 4', 500], ['Account 5', 0], ['Account 6', -50]]);

            $statement = $this->connection->query($query);
            $this->assertEquals(3, $statement->getRowCount());
            $this->assertEquals(5, $this->connection->getRowCount('accounts'));
        });
    }

    /**
     * Assert that an exception is thrown when inserting data into a table that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnTableNotExists()
    {
        $query = $this->connection
            ->insert()
            ->into('table_not_exists')
            ->columns(['name'])
            ->values(['name1']);

        $this->connection->query($query);
    }

    /**
     * Assert that an exception is thrown when inserting data into a column that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnColumnNotExists()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->insert()
                ->into('accounts')
                ->columns(['column_not_exists'])
                ->values(['value']);

            $this->connection->query($query);
        });
    }

    /**
     * Assert that an exception is thrown when inserting duplicate data.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnDuplicateInsert()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->insert()
                ->into('accounts')
                ->columns(['name'])
                ->values(['Account 1']);

            $this->connection->query($query);
        });
    }
}
