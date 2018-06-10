<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Data;

use Guvra\Builder\Parameter;
use Tests\AbstractTestCase;

/**
 * Test the DELETE query builder.
 */
class DeleteTest extends AbstractTestCase
{
    /**
     * Test the DELETE query builder.
     */
    public function testDelete()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->delete()
                ->from('accounts')
                ->where('name', '=', new Parameter('name'));

            $statement = $this->connection->prepare($query);
            $statement->execute([':name' => 'Account 1']);
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
