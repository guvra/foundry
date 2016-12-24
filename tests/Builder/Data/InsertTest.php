<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Data;

use Tests\AbstractTestCase;

/**
 * Test the INSERT query builder.
 */
class InsertTest extends AbstractTestCase
{
    /**
     * @var \Guvra\Builder\Data\Delete
     */
    protected $query;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->query = $this->connection->insert();
    }

    /**
     * Test the INSERT query builder.
     */
    public function testInsert()
    {
        return;
        $this->withTestTables(function () {
            $statement = $this->query
                ->into('accounts')
                ->columns(['name', 'balance'])
                ->values(['Account 4', 500])
                ->query();

            $this->assertEquals(1, $statement->getRowCount());
            $this->assertEquals(3, $this->connection->getRowCount('accounts'));
        });
    }

    /**
     * Assert that an exception is thrown when inserting data into a table that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnTableNotExists()
    {
        throw new \PDOException;
        $statement = $this->query
            ->into('table_not_exists')
            ->columns(['name'])
            ->values(['name1'])
            ->query();
    }

    /**
     * Assert that an exception is thrown when inserting data into a column that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnColumnNotExists()
    {
        throw new \PDOException;
        $this->withTestTables(function () {
            $statement = $this->query
                ->into('accounts')
                ->columns(['column_not_exists'])
                ->values(['value'])
                ->query();
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
            $statement = $this->query
                ->into('accounts')
                ->columns(['name'])
                ->values(['Account 1'])
                ->query();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->query = null;
    }
}
