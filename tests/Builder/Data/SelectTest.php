<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Data;

use Guvra\Builder\Data\Select;
use Guvra\Builder\Expression;
use Tests\AbstractTestCase;

/**
 * Test the SELECT query builder.
 */
class SelectTest extends AbstractTestCase
{
    /**
     * Test the SELECT query builder.
     */
    public function testSelect()
    {
        $this->withTestTables(function () {
            // Basic select
            $statement = $this->connection
                ->select()
                ->from('accounts')
                ->where('name', '=', 'Account 1')
                ->group('account_id')
                ->order('name', 'asc')
                ->limit(10)
                ->query();

            $rows = $statement->fetchAll();
            $this->assertEquals(1, count($rows));
            $this->assertEquals('Account 1', $rows[0]['name']);
        });
    }

    /**
     * Test the SELECT query builder.
     */
    public function testJoinSelect()
    {
        $this->withTestTables(function () {
            // Basic join
            $select = $this->connection
                ->select()
                ->from('transactions')
                ->join('accounts', 'accounts.account_id', '=', 'transactions.account_id')
                ->where('name', '=', 'Account 1');

            $rows = $select->query()->fetchAll();
            $this->assertEquals(6, count($rows));
            $this->assertEquals('Transaction 1', $rows[0]['description']);

            // Join with callback
            $select
                ->reset(Select::PART_JOIN)
                ->join('accounts', function ($condition) {
                    $condition->where('accounts.account_id', '=', new Expression('transactions.account_id'));
                });

            $rows = $select->query()->fetchAll();
            $this->assertEquals(6, count($rows));
            $this->assertEquals('Transaction 1', $rows[0]['description']);
        });
    }

    /**
     * Test the condition builder.
     */
    public function testConditionGroup()
    {
        $this->withTestTables(function () {
            $statement = $this->connection
                ->select()
                ->from('transactions')
                ->where('account_id', '=', 1)
                ->where(function ($condition) {
                    $condition->where('amount < 0 OR amount > 100')
                        ->orWhere('amount', '=', 40);
                })
                ->query();

            $rows = $statement->fetchAll();
            $this->assertEquals(4, count($rows));
        });
    }

    /**
     * Assert that an exception is thrown when updating data in a table that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnTableNotExists()
    {
        $statement =  $this->connection
            ->select()
            ->from('table_not_exists')
            ->where('name', '=', 'Account 1')
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
                ->select()
                ->from('accounts')
                ->where('column_not_exists', '=', 'value')
                ->query();
        });
    }
}
