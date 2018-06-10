<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Data;

use Tests\AbstractTestCase;

/**
 * Test the UPDATE query builder.
 */
class UpdateTest extends AbstractTestCase
{
    /**
     * Test the UPDATE query builder.
     */
    public function testUpdate()
    {
        $this->withTestTables(function () {
            $statement = $this->connection
                ->update()
                ->table('accounts')
                ->values(['name' => 'Account 5'])
                ->where('name', '=', 'Account 1')
                ->query();

            $this->assertEquals(1, $statement->getRowCount());
        });
    }

    /**
     * Assert that an exception is thrown when updating data in a table that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnTableNotExists()
    {
        $statement = $this->connection
            ->update()
            ->table('table_not_exists')
            ->values(['name' => 'Account 5'])
            ->query();
    }

    /**
     * Assert that an exception is thrown when updating data in a column that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnColumnNotExists()
    {
        $this->withTestTables(function () {
            $statement = $this->connection
                ->update()
                ->table('accounts')
                ->values(['column_not_exists' => 'value'])
                ->query();
        });
    }
}
