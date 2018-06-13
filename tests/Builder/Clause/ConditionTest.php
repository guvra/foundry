<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Statement;

use Guvra\Builder\Clause\ConditionGroup;
use Guvra\Builder\Expression;
use Guvra\Builder\Statement\Select;
use Tests\AbstractTestCase;

/**
 * Test the condition query builder.
 */
class ConditionTest extends AbstractTestCase
{
    public function testFrom()
    {
        $query = $this->select()->from('accounts');
        $this->assertEquals('SELECT * FROM accounts', $query);

        $query->from(['a' => 'accounts']);
        $this->assertEquals('SELECT * FROM accounts AS a', $query);

        $query->from(['accounts', 'transactions']);
        $this->assertEquals('SELECT * FROM accounts, transactions', $query);

        $query->from(['a' => 'accounts', 't' => 'transactions']);
        $this->assertEquals('SELECT * FROM accounts AS a, transactions AS t', $query);

        $query->reset(SELECT::PART_FROM);
        $this->assertEquals('SELECT *', $query);
    }

    public function testColumns()
    {
        $query = $this->select();
        $this->assertEquals('SELECT *', $query);

        $query->columns('name');
        $this->assertEquals('SELECT name', $query);

        $query->columns('accounts.name');
        $this->assertEquals('SELECT accounts.name', $query);

        $query->columns(['accounts.name', 'transactions.amount']);
        $this->assertEquals('SELECT accounts.name, transactions.amount', $query);

        $query->columns(['name' => 'accounts.name', 'amount' => 'transactions.amount']);
        $this->assertEquals('SELECT accounts.name AS name, transactions.amount AS amount', $query);

        $query->columns(['min' => 'MIN(amount)']);
        $this->assertEquals('SELECT MIN(amount) AS min', $query);

        $query->reset(SELECT::PART_COLUMNS);
        $this->assertEquals('SELECT *', $query);
    }

    public function testGroupBy()
    {
        $query = $this->select()->group('account_id');
        $this->assertEquals('SELECT * GROUP BY account_id', $query);

        $query->group('category_id');
        $this->assertEquals('SELECT * GROUP BY account_id, category_id', $query);

        $query->group(['transaction_id', 'user_id']);
        $this->assertEquals('SELECT * GROUP BY account_id, category_id, transaction_id, user_id', $query);

        $query->reset(SELECT::PART_GROUP);
        $query->group(['account_id', 'category_id']);
        $this->assertEquals('SELECT * GROUP BY account_id, category_id', $query);

        $query->reset(SELECT::PART_GROUP);
        $this->assertEquals('SELECT *', $query);
    }

    public function testOrderBy()
    {
        $query = $this->select()->order('amount', 'desc');
        $this->assertEquals('SELECT * ORDER BY amount DESC', $query);

        $query->order('name', 'asc');
        $this->assertEquals('SELECT * ORDER BY amount DESC, name ASC', $query);

        $query->reset(SELECT::PART_ORDER);
        $this->assertEquals('SELECT *', $query);
    }

    public function testLimit()
    {
        $query = $this->select()->limit(0, 0);
        $this->assertEquals('SELECT *', $query);

        $query->limit(10);
        $this->assertEquals('SELECT * LIMIT 10', $query);

        $query->limit(10, 0);
        $this->assertEquals('SELECT * LIMIT 10', $query);

        $query->limit(0, 100);
        $this->assertEquals('SELECT * OFFSET 100', $query);

        $query->limit(10, 100);
        $this->assertEquals('SELECT * LIMIT 10 OFFSET 100', $query);

        $query->reset(SELECT::PART_LIMIT);
        $this->assertEquals('SELECT *', $query);
    }

    public function testJoin()
    {
        // Tests only the HasJoin trait, Join/JoinGroup builders are tested in a separate file
        $query = $this->select()->join('accounts');
        $this->assertEquals('SELECT * JOIN accounts', $query);
        $query->join(['c' => 'categories'], 'c.category_id', '=', 't.category_id');
        $this->assertEquals('SELECT * JOIN accounts JOIN categories AS c ON c.category_id = t.category_id', $query);

        $query = $this->select()->joinLeft('accounts');
        $this->assertEquals('SELECT * LEFT JOIN accounts', $query);
        $query->joinLeft(['c' => 'categories'], 'c.category_id', '=', 't.category_id');
        $this->assertEquals('SELECT * LEFT JOIN accounts LEFT JOIN categories AS c ON c.category_id = t.category_id', $query);

        $query->reset(SELECT::PART_JOIN);
        $query = $this->select()->joinRight('accounts');
        $this->assertEquals('SELECT * RIGHT JOIN accounts', $query);
        $query->joinRight(['c' => 'categories'], 'c.category_id', '=', 't.category_id');
        $this->assertEquals('SELECT * RIGHT JOIN accounts RIGHT JOIN categories AS c ON c.category_id = t.category_id', $query);

        $query->reset(SELECT::PART_JOIN);
        $query->joinCross('accounts');
        $query->joinNatural(['c' => 'categories']);
        $this->assertEquals('SELECT * CROSS JOIN accounts NATURAL JOIN categories AS c', $query);

        $query->reset(SELECT::PART_JOIN);
        $this->assertEquals('SELECT *', $query);
    }

    public function testWhere()
    {
        // Tests only the HasWhere trait, Condition/ConditionGroup builders are tested in a separate file
        $query = $this->select()->where('amount', '>', 1000);
        $this->assertEquals('SELECT * WHERE (amount > 1000)', $query);
        $query->orWhere('amount', '<', 1000);
        $this->assertEquals('SELECT * WHERE (amount > 1000) OR (amount < 1000)', $query);

        $query->reset(SELECT::PART_WHERE);
        $subQuery = $this->select()->from('accounts')->columns('account_id');
        $query->whereExists($subQuery)->orWhereExists($subQuery)
            ->whereNotExists($subQuery)->orWhereNotExists($subQuery);
        $this->assertWhereEquals("{operator} ($subQuery)", $query, 'EXISTS', 'NOT EXISTS');

        $query->reset(SELECT::PART_WHERE);
        $query->whereNull('name')->orWhereNull('name')
            ->whereNotNull('name')->orWhereNotNull('name');
        $this->assertWhereEquals('name {operator}', $query, 'IS NULL', 'IS NOT NULL');

        $query->reset(SELECT::PART_WHERE);
        $query->whereBetween('amount', 0, 1000)->orWhereBetween('amount', 0, 1000)
            ->whereNotBetween('amount', 0, 1000)->orWhereNotBetween('amount', 0, 1000);
        $this->assertWhereEquals('amount {operator} 0 AND 1000', $query, 'BETWEEN', 'NOT BETWEEN');

        $query->reset(SELECT::PART_WHERE);
        $query->whereIn('amount', [1, 2, 3])->orWhereIn('amount', [1, 2, 3])
            ->whereNotIn('amount', [1, 2, 3])->orWhereNotIn('amount', [1, 2, 3]);
        $this->assertWhereEquals('amount {operator} (1,2,3)', $query, 'IN', 'NOT IN');
    }

    public function testHaving()
    {
        // Tests only the HasHaving trait, Condition/ConditionGroup builders are tested in a separate file
        $query = $this->select()->having('amount', '>', 1000);
        $this->assertEquals('SELECT * HAVING (amount > 1000)', $query);
        $query->orHaving('amount', '<', 1000);
        $this->assertEquals('SELECT * HAVING (amount > 1000) OR (amount < 1000)', $query);

        $query->reset(SELECT::PART_HAVING);
        $subQuery = $this->select()->from('accounts')->columns('account_id');
        $query->havingExists($subQuery)->orHavingExists($subQuery)
            ->havingNotExists($subQuery)->orHavingNotExists($subQuery);
        $this->assertWhereEquals("{operator} ($subQuery)", $query, 'EXISTS', 'NOT EXISTS', 'HAVING');

        $query->reset(SELECT::PART_HAVING);
        $query->havingNull('name')->orHavingNull('name')
            ->havingNotNull('name')->orHavingNotNull('name');
        $this->assertWhereEquals('name {operator}', $query, 'IS NULL', 'IS NOT NULL', 'HAVING');

        $query->reset(SELECT::PART_HAVING);
        $query->havingBetween('amount', 0, 1000)->orHavingBetween('amount', 0, 1000)
            ->havingNotBetween('amount', 0, 1000)->orHavingNotBetween('amount', 0, 1000);
        $this->assertWhereEquals('amount {operator} 0 AND 1000', $query, 'BETWEEN', 'NOT BETWEEN', 'HAVING');

        $query->reset(SELECT::PART_HAVING);
        $query->havingIn('amount', [1, 2, 3])->orHavingIn('amount', [1, 2, 3])
            ->havingNotIn('amount', [1, 2, 3])->orHavingNotIn('amount', [1, 2, 3]);
        $this->assertWhereEquals('amount {operator} (1,2,3)', $query, 'IN', 'NOT IN', 'HAVING');
    }

    /**
     * Test the SELECT query builder.
     */
    public function testSelect()
    {
        $this->withTestTables(function () {
            // Basic select
            $query = $this->connection
                ->select()
                ->from('accounts')
                ->where('name', '=', 'Account 1')
                ->group('account_id')
                ->order('name', 'asc')
                ->limit(10);

            $statement = $this->connection->query($query);
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
            $query = $this->connection
                ->select()
                ->from('transactions')
                ->join('accounts', 'accounts.account_id', '=', 'transactions.account_id')
                ->where('name', '=', 'Account 1');

            $statement = $this->connection->query($query);
            $rows = $statement->fetchAll();
            $this->assertEquals(6, count($rows));
            $this->assertEquals('Transaction 1', $rows[0]['description']);

            // Join with callback
            $query
                ->reset(Select::PART_JOIN)
                ->join('accounts', function (ConditionGroup $condition) {
                    $condition->where('accounts.account_id', '=', new Expression('transactions.account_id'));
                });

            $statement = $this->connection->query($query);
            $rows = $statement->fetchAll();
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
            $query = $this->connection
                ->select()
                ->from('transactions')
                ->where('account_id', '=', 1)
                ->where(function (ConditionGroup $condition) {
                    $condition->where('amount < 0 OR amount > 100')
                        ->orWhere('amount', '=', 40);
                });

            $statement = $this->connection->query($query);
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
        $query = $this->connection
            ->select()
            ->from('table_not_exists')
            ->where('name', '=', 'Account 1');

        $this->connection->query($query);
    }

    /**
     * Assert that an exception is thrown when updating data in a column that does not exist.
     *
     * @expectedException \PDOException
     */
    public function testExceptionOnColumnNotExists()
    {
        $this->withTestTables(function () {
            $query = $this->connection
                ->select()
                ->from('accounts')
                ->where('column_not_exists', '=', 'value');

            $this->connection->query($query);
        });
    }

    /**
     * Method to tests where/having traits without having to write hundreds of lines of code.
     *
     * @param string $expectedQueryPart
     * @param Select $actualQuery
     * @param string $operator
     * @param string $notOperator
     * @param string $clause
     */
    private function assertWhereEquals(
        string $expectedQueryPart,
        Select $actualQuery,
        string $operator,
        string $notOperator,
        string $clause = 'WHERE'
    ) {
        $queryPartWithOperator = str_replace('{operator}', $operator, $expectedQueryPart);
        $queryPartWithNotOperator = str_replace('{operator}', $notOperator, $expectedQueryPart);

        $expectedQuery = '(' . $queryPartWithOperator . ')';
        $expectedQuery .= ' OR (' . $queryPartWithOperator. ')';
        $expectedQuery .= ' AND (' . $queryPartWithNotOperator . ')';
        $expectedQuery .= ' OR (' . $queryPartWithNotOperator . ')';
        $expectedQuery = str_replace(['{operator}', '{notOperator}'], [$operator, $notOperator], $expectedQuery);
        $expectedQuery = "SELECT * $clause " . $expectedQuery;

        $this->assertEquals($expectedQuery, $actualQuery);
    }

    /**
     * @return Select
     */
    private function select()
    {
        return $this->connection->select();
    }
}
