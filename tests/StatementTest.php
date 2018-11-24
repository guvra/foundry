<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests;

/**
 * Test the statements.
 */
class StatementTest extends TestCase
{
    public function testFetchAll()
    {
        $this->withTestTables(function () {
            $query = $this->createSelect()->from('accounts');

            $statement = $this->connection->query($query);
            $values = $statement->fetchAll();
            $this->assertCount(2, $values);

            $expectedValue = [
                ['account_id' => 1, 'name' => 'Account 1', 'balance' => 0.0],
                ['account_id' => 2, 'name' => 'Account 2', 'balance' => 100.0],
            ];
            $this->assertEquals($expectedValue, $values);

            // Also test the underlying PDO object
            $this->assertInstanceOf(\PDOStatement::class, $statement->getPdoStatement());
        });
    }

    public function testFetchRow()
    {
        $this->withTestTables(function () {
            $query = $this->createSelect()->from('accounts');

            $statement = $this->connection->query($query);
            $value = $statement->fetchRow();
            $this->assertCount(3, $value);

            $expectedValue = ['account_id' => 1, 'name' => 'Account 1', 'balance' => 0.0];
            $this->assertEquals($expectedValue, $value);
        });
    }

    public function testFetchColumn()
    {
        $this->withTestTables(function () {
            $query = $this->createSelect()->from('accounts');

            $statement = $this->connection->query($query);
            $values = $statement->fetchColumn();
            $this->assertCount(2, $values);

            $expectedValue = [1, 2];
            $this->assertEquals($expectedValue, $values);
        });
    }

    public function testFetchOne()
    {
        $this->withTestTables(function () {
            $query = $this->createSelect()->from('accounts')->columns('account_id');

            $statement = $this->connection->query($query);
            $value = $statement->fetchOne();
            $this->assertEquals(1, $value);
        });
    }
}
