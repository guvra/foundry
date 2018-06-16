<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests;

use Foundry\Builder\BuilderFactory;
use Foundry\Builder\Statement\Delete;
use Foundry\Builder\Statement\Insert;
use Foundry\Builder\Statement\Select;
use Foundry\Builder\Statement\Update;
use Foundry\Connection;
use Foundry\Expression;
use Foundry\Parameter;

/**
 * Test the connection bag.
 */
class ConnectionTest extends TestCase
{
    public function testDriver()
    {
        $this->assertEquals('sqlite', $this->connection->getDriver());
    }

    public function testBuilderFactory()
    {
        $this->assertInstanceOf(BuilderFactory::class, $this->connection->getBuilderFactory());
    }

    public function testQueryBuilders()
    {
        $this->assertInstanceOf(Select::class, $this->connection->select());
        $this->assertInstanceOf(Insert::class, $this->connection->insert());
        $this->assertInstanceOf(Update::class, $this->connection->update());
        $this->assertInstanceOf(Delete::class, $this->connection->delete());
    }

    public function testInternalPdoObject()
    {
        $this->assertInstanceOf(\Pdo::class, $this->connection->getPdo());
    }

    public function testQuery()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->delete()
                ->from('accounts')
                ->where('name', '=', 'Account 1');

            $statement = $this->connection->query($query);
            $this->assertEquals(1, $statement->getRowCount());
            $this->assertEquals(1, $this->connection->getRowCount('accounts'));
        });
    }

    public function testQueryWithBind()
    {
        $this->withTestTables(function () {
            $bind = [':name' => 'Account 1'];
            $query = $this->connection
                ->delete()
                ->from('accounts')
                ->where('name', '=', new Parameter('name'));

            $statement = $this->connection->query($query, $bind);
            $this->assertEquals(1, $statement->getRowCount());
            $this->assertEquals(1, $this->connection->getRowCount('accounts'));
        });
    }

    public function testExec()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->insert()
                ->into('accounts')
                ->columns(['name', 'balance'])
                ->values(['Account 3', 500]);

            $rowCount = $this->connection->exec($query);
            $this->assertEquals(1, $rowCount);
            $this->assertEquals(3, $this->connection->getRowCount('accounts'));
        });
    }

    public function testPrepare()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->update()
                ->table('accounts')
                ->values(['name' => 'Account 3'])
                ->where('name', '=', 'Account 1');

            $statement = $this->connection->prepare($query);
            $statement->execute();
            $this->assertEquals(1, $statement->getRowCount());
        });
    }

    public function testPrepareWithBind()
    {
        $this->withTestTables(function () {
            $bind = ['Account 3', 'Account 1'];
            $query = $this->connection
                ->update()
                ->table('accounts')
                ->values(['name' => new Parameter])
                ->where('name', '=', new Parameter);

            $statement = $this->connection->prepare($query);
            $statement->execute($bind);
            $this->assertEquals(1, $statement->getRowCount());
        });
    }

    public function testRowCount()
    {
        $this->withTestTables(function () {
            // Without callback
            $this->assertEquals(2, $this->connection->getRowCount('accounts'));

            // With callback
            $this->assertEquals(1, $this->connection->getRowCount('accounts', function (Select $query) {
                $query->where('account_id', '=', 1);
            }));
        });
    }

    public function testLastInsertId()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->insert()
                ->into('accounts')
                ->columns(['name', 'balance'])
                ->values(['Account 3', 500]);

            $this->connection->query($query);
            $this->assertEquals(3, $this->connection->getLastInsertId());
        });
    }

    public function testQuote()
    {
        $unquotedValue = 'this is a string';
        $quotedValue = $this->connection->quote($unquotedValue);
        $this->assertEquals($this->connection->getPdo()->quote($unquotedValue), $quotedValue);

        $parameter = new Parameter('name');
        $this->assertEquals($parameter->toString(), $this->connection->quote($parameter));

        $expression = new Expression('MAX(amount');
        $this->assertEquals($expression->toString(), $this->connection->quote($expression));
    }

    public function testTransactionRollback()
    {
        $this->withTestTables(function () {
            $this->connection->beginTransaction();

            $query = $this->connection
                ->insert()
                ->into('accounts')
                ->columns(['name', 'balance'])
                ->values(['Account 3', 500]);

            $this->connection->query($query);
            $this->connection->rollbackTransaction();
            $this->assertEquals(2, $this->connection->getRowCount('accounts'));
        });
    }

    public function testTransactionCommit()
    {
        $this->withTestTables(function () {
            $this->connection->beginTransaction();

            $query = $this->connection
                ->insert()
                ->into('accounts')
                ->columns(['name', 'balance'])
                ->values(['Account 3', 500]);

            $this->connection->query($query);
            $this->connection->commitTransaction();
            $this->assertEquals(3, $this->connection->getRowCount('accounts'));
        });
    }

    /**
     * @expectedException \PDOException
     */
    public function testInvalidDriver()
    {
        new Connection(['dsn' => 'notexists']);
    }
}
