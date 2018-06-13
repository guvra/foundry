<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Statement;

use Guvra\Builder\Statement\Select;
use Tests\AbstractTestCase;

/**
 * Test the SELECT query builder.
 */
class SelectTest extends AbstractTestCase
{
    public function testDistinct()
    {
        $query = $this->createSelect()->distinct(false);
        $this->assertEquals('SELECT * FROM accounts', $query);

        $query->reset(Select::PART_DISTINCT);
        $query->distinct();
        $this->assertEquals('SELECT DISTINCT * FROM accounts', $query);

        $query->reset(Select::PART_DISTINCT);
        $query->distinct(true);
        $this->assertEquals('SELECT DISTINCT * FROM accounts', $query);
    }

    public function testFrom()
    {
        $query = $this->createSelect()->from('accounts');
        $this->assertEquals('SELECT * FROM accounts', $query);

        $query->from(['a' => 'accounts']);
        $this->assertEquals('SELECT * FROM accounts AS a', $query);

        $query->from(['accounts', 'transactions']);
        $this->assertEquals('SELECT * FROM accounts, transactions', $query);

        $query->from(['a' => 'accounts', 't' => 'transactions']);
        $this->assertEquals('SELECT * FROM accounts AS a, transactions AS t', $query);
    }

    public function testColumns()
    {
        $query = $this->createSelect();
        $this->assertEquals('SELECT * FROM accounts', $query);

        $query->columns('name');
        $this->assertEquals('SELECT name FROM accounts', $query);

        $query->columns('accounts.name');
        $this->assertEquals('SELECT accounts.name FROM accounts', $query);

        $query->columns(['accounts.name', 'accounts.balance']);
        $this->assertEquals('SELECT accounts.name, accounts.balance FROM accounts', $query);

        $query->columns(['n' => 'accounts.name', 'b' => 'accounts.balance']);
        $this->assertEquals('SELECT accounts.name AS n, accounts.balance AS b FROM accounts', $query);

        $query->columns(['min' => 'MIN(balance)']);
        $this->assertEquals('SELECT MIN(balance) AS min FROM accounts', $query);

        $query->reset(SELECT::PART_COLUMNS);
        $this->assertEquals('SELECT * FROM accounts', $query);

        $query->reset(Select::PART_FROM);
        $query->columns(1);
        $this->assertEquals('SELECT 1', $query);
    }

    public function testSubQueryColumn()
    {
        $query = $this->createSelect();
        $query->columns(['max' => $this->createSelect()->from('accounts')->columns('MAX(balance)')]);
        $this->assertEquals('SELECT (SELECT MAX(balance) FROM accounts) AS max FROM accounts', $query);
    }

    public function testGroupBy()
    {
        $query = $this->createSelect()->from('transactions')->group('account_id');
        $this->assertEquals('SELECT * FROM transactions GROUP BY account_id', $query);

        $query->group('category_id');
        $this->assertEquals('SELECT * FROM transactions GROUP BY account_id, category_id', $query);

        $query->reset(Select::PART_GROUP);
        $query->group(['category_id', 'account_id']);
        $this->assertEquals('SELECT * FROM transactions GROUP BY category_id, account_id', $query);

        $query->reset(Select::PART_GROUP);
        $this->assertEquals('SELECT * FROM transactions', $query);
    }

    public function testOrderBy()
    {
        $query = $this->createSelect()->order('balance', 'desc');
        $this->assertEquals('SELECT * FROM accounts ORDER BY balance DESC', $query);

        $query->order('name', 'asc');
        $this->assertEquals('SELECT * FROM accounts ORDER BY balance DESC, name ASC', $query);

        $query->reset(SELECT::PART_ORDER);
        $this->assertEquals('SELECT * FROM accounts', $query);
    }

    public function testLimit()
    {
        $query = $this->createSelect()->limit(0, 0);
        $this->assertEquals('SELECT * FROM accounts', $query);

        $query->limit(10);
        $this->assertEquals('SELECT * FROM accounts LIMIT 10', $query);

        $query->limit(10, 0);
        $this->assertEquals('SELECT * FROM accounts LIMIT 10', $query);

        $query->limit(0, 100);
        $this->assertEquals('SELECT * FROM accounts OFFSET 100', $query);

        $query->limit(10, 100);
        $this->assertEquals('SELECT * FROM accounts LIMIT 10 OFFSET 100', $query);

        $query->reset(SELECT::PART_LIMIT);
        $this->assertEquals('SELECT * FROM accounts', $query);
    }

    public function testJoin()
    {
        // Tests only the HasJoin trait, Join/JoinGroup builders are tested in a separate file
        $query = $this->createSelect()->join('transactions');
        $this->assertEquals('SELECT * FROM accounts JOIN transactions', $query);
        $query->join(['c' => 'categories'], 'c.category_id', '=', 't.category_id');
        $this->assertEquals('SELECT * FROM accounts JOIN transactions JOIN categories AS c ON c.category_id = t.category_id', $query);

        $query->reset(SELECT::PART_JOIN);
        $query->joinLeft('transactions');
        $this->assertEquals('SELECT * FROM accounts LEFT JOIN transactions', $query);
        $query->joinLeft(['c' => 'categories'], 'c.category_id', '=', 't.category_id');
        $this->assertEquals('SELECT * FROM accounts LEFT JOIN transactions LEFT JOIN categories AS c ON c.category_id = t.category_id', $query);

        $query->reset(SELECT::PART_JOIN);
        $query->joinRight('transactions');
        $this->assertEquals('SELECT * FROM accounts RIGHT JOIN transactions', $query);
        $query->joinRight(['c' => 'categories'], 'c.category_id', '=', 't.category_id');
        $this->assertEquals('SELECT * FROM accounts RIGHT JOIN transactions RIGHT JOIN categories AS c ON c.category_id = t.category_id', $query);

        $query->reset(SELECT::PART_JOIN);
        $query->joinCross('transactions');
        $query->joinNatural(['c' => 'categories']);
        $this->assertEquals('SELECT * FROM accounts CROSS JOIN transactions NATURAL JOIN categories AS c', $query);

        $query->reset(SELECT::PART_JOIN);
        $query->join('transactions', 'transactions.account_id = accounts.account_id');
        $this->assertEquals('SELECT * FROM accounts JOIN transactions ON transactions.account_id = accounts.account_id', $query);

        $query->reset(SELECT::PART_JOIN);
        $this->assertEquals('SELECT * FROM accounts', $query);
    }

    public function testWhere()
    {
        // Tests only the HasWhere trait, Condition/ConditionGroup builders are tested in a separate file
        $query = $this->createSelect()->where('balance', '>', 1000);
        $this->assertEquals('SELECT * FROM accounts WHERE (balance > 1000)', $query);
        $query->orWhere('balance', '<', 1000);
        $this->assertEquals('SELECT * FROM accounts WHERE (balance > 1000) OR (balance < 1000)', $query);

        $query->reset(SELECT::PART_WHERE);
        $subQuery = $this->createSelect()->columns('account_id');
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

        $query->reset(SELECT::PART_WHERE);
        $this->assertEquals('SELECT * FROM accounts', $query);
    }

    public function testHaving()
    {
        // Tests only the HasHaving trait, Condition/ConditionGroup builders are tested in a separate file
        $query = $this->createSelect()->having('balance', '>', 1000);
        $this->assertEquals('SELECT * FROM accounts HAVING (balance > 1000)', $query);
        $query->orHaving('balance', '<', 1000);
        $this->assertEquals('SELECT * FROM accounts HAVING (balance > 1000) OR (balance < 1000)', $query);

        $query->reset(SELECT::PART_HAVING);
        $subQuery = $this->createSelect()->columns('account_id');
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

        $query->reset(SELECT::PART_HAVING);
        $this->assertEquals('SELECT * FROM accounts', $query);
    }

    public function testUnion()
    {
        $query = $this->createSelect();

        $query->union($this->createSelect()->from('accs'));
        $this->assertEquals('SELECT * FROM accounts UNION SELECT * FROM accs', $query);

        $query->union($this->createSelect()->from('a'), true);
        $this->assertEquals('SELECT * FROM accounts UNION SELECT * FROM accs UNION ALL SELECT * FROM a', $query);

        $query->reset(SELECT::PART_UNION);
        $this->assertEquals('SELECT * FROM accounts', $query);
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
        $expectedQuery = "SELECT * FROM accounts $clause " . $expectedQuery;

        $this->assertEquals($expectedQuery, $actualQuery);
    }
}
