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
 * Test the DELETE query builder.
 */
class DeleteTest extends AbstractTestCase
{
    /**
     * @var \Guvra\Builder\Data\Delete
     */
    protected $query;

    /**
     * Test the DELETE query builder.
     */
    public function testDelete()
    {
        $this->withTestTables(function () {
            $statement = $this->connection
                ->delete()
                ->from('accounts')
                ->where('name', '=', 'Account 1')
                ->query();

            $this->assertEquals(1, $statement->getRowCount());
            $this->assertEquals(1, $this->connection->getRowCount('accounts'));
        });
    }

    /**
     * Assert that an exception is thrown when deleting data from a table that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnTableNotExists()
    {
        $statement = $this->connection
            ->delete()
            ->from('table_not_exists')
            ->query();
    }

    /**
     * Assert that an exception is thrown when deleting data from a column that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnColumnNotExists()
    {
        $this->withTestTables(function () {
            $statement = $this->connection
                ->delete()
                ->from('accounts')
                ->where('column_not_exists', '=', 'value')
                ->query();
        });
    }
}
