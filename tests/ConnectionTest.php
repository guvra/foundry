<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests;

use Guvra\Builder\Data\Select;

/**
 * Test the connection bag.
 */
class ConnectionTest extends AbstractTestCase
{
    /**
     * Assert that the database driver name is found.
     */
    public function testDriver()
    {
        $this->assertEquals('sqlite', $this->connection->getDriver());
    }

    /**
     * Test that the query builders are properly created.
     */
    public function testQueryBuilders()
    {
        $this->assertInstanceOf('Guvra\Builder\Data\Select', $this->connection->select());
        $this->assertInstanceOf('Guvra\Builder\Data\Insert', $this->connection->insert());
        $this->assertInstanceOf('Guvra\Builder\Data\Update', $this->connection->update());
        $this->assertInstanceOf('Guvra\Builder\Data\Delete', $this->connection->delete());
    }

    /**
     * Test the getRowCount method.
     */
    public function testRowCount()
    {
        $this->withTestTables(function () {
            $this->assertEquals(2, $this->connection->getRowCount('accounts'));
            $this->assertEquals(12, $this->connection->getRowCount('transactions'));
        });
    }
}
