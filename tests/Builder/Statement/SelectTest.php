<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder\Statement;

use Foundry\Builder\Clause\Having;
use Foundry\Builder\Clause\Join;
use Foundry\Builder\Clause\Select\Columns;
use Foundry\Builder\Clause\Select\Distinct;
use Foundry\Builder\Clause\Select\From;
use Foundry\Builder\Clause\Select\Group;
use Foundry\Builder\Clause\Select\Limit;
use Foundry\Builder\Clause\Select\Order;
use Foundry\Builder\Clause\Select\Union;
use Foundry\Builder\Clause\Where;
use Foundry\Builder\Statement\Select;
use Foundry\Tests\TestCase;

/**
 * Test the SELECT query builder.
 */
class SelectTest extends TestCase
{
    public function testDistinct()
    {
        $query = $this->createSelect()->from('accounts')->distinct(false);
        $this->assertCompiles('SELECT * FROM accounts', $query);

        $query->reset(Select::PART_DISTINCT);
        $query->distinct();
        $this->assertCompiles('SELECT DISTINCT * FROM accounts', $query);

        $query->reset(Select::PART_DISTINCT);
        $query->distinct(true);
        $this->assertCompiles('SELECT DISTINCT * FROM accounts', $query);
    }

    public function testFrom()
    {
        $query = $this->createSelect()->from('accounts');
        $this->assertCompiles('SELECT * FROM accounts', $query);

        $query->from(['c' => 'categories']);
        $this->assertCompiles('SELECT * FROM accounts, categories AS c', $query);

        $query->reset(Select::PART_FROM);
        $query->from(['accounts', 'transactions']);
        $this->assertCompiles('SELECT * FROM accounts, transactions', $query);

        $query->reset(Select::PART_FROM);
        $query->from(['a' => 'accounts', 't' => 'transactions']);
        $this->assertCompiles('SELECT * FROM accounts AS a, transactions AS t', $query);

        $query->reset(Select::PART_FROM);
        $this->assertCompiles('SELECT *', $query);
    }

    public function testColumns()
    {
        $query = $this->createSelect()->from('accounts');
        $this->assertCompiles('SELECT * FROM accounts', $query);

        $query->columns('name');
        $this->assertCompiles('SELECT name FROM accounts', $query);

        $query->columns('accounts.balance');
        $this->assertCompiles('SELECT name, accounts.balance FROM accounts', $query);

        $query->reset(Select::PART_COLUMNS);
        $query->columns(['accounts.name', 'accounts.balance']);
        $this->assertCompiles('SELECT accounts.name, accounts.balance FROM accounts', $query);

        $query->reset(Select::PART_COLUMNS);
        $query->columns(['n' => 'accounts.name', 'b' => 'accounts.balance']);
        $this->assertCompiles('SELECT accounts.name AS n, accounts.balance AS b FROM accounts', $query);

        $query->reset(Select::PART_COLUMNS);
        $query->columns(['min' => 'MIN(balance)']);
        $this->assertCompiles('SELECT MIN(balance) AS min FROM accounts', $query);

        $query->reset(Select::PART_COLUMNS);
        $this->assertCompiles('SELECT * FROM accounts', $query);

        $query->reset(Select::PART_FROM);
        $query->columns(1);
        $this->assertCompiles('SELECT 1', $query);
    }

    public function testSubQueryColumn()
    {
        $query = $this->createSelect()->from('accounts');
        $query->columns(['max' => $this->createSelect()->from('accounts')->columns('MAX(balance)')]);
        $this->assertCompiles('SELECT (SELECT MAX(balance) FROM accounts) AS max FROM accounts', $query);
    }

    public function testGroupBy()
    {
        $query = $this->createSelect();
        $query->from('transactions')->group('account_id');
        $this->assertCompiles('SELECT * FROM transactions GROUP BY account_id', $query);

        $query->group('category_id');
        $this->assertCompiles('SELECT * FROM transactions GROUP BY account_id, category_id', $query);

        $query->reset(Select::PART_GROUP);
        $query->group(['category_id', 'account_id']);
        $this->assertCompiles('SELECT * FROM transactions GROUP BY category_id, account_id', $query);

        $query->reset(Select::PART_GROUP);
        $this->assertCompiles('SELECT * FROM transactions', $query);
    }

    public function testOrderBy()
    {
        $query = $this->createSelect()->from('accounts')->order('balance DESC');
        $this->assertCompiles('SELECT * FROM accounts ORDER BY balance DESC', $query);

        $query->order('name ASC');
        $this->assertCompiles('SELECT * FROM accounts ORDER BY balance DESC, name ASC', $query);

        $query->reset(Select::PART_ORDER);
        $query->order(['balance ASC', 'name DESC']);
        $this->assertCompiles('SELECT * FROM accounts ORDER BY balance ASC, name DESC', $query);

        $query->reset(SELECT::PART_ORDER);
        $this->assertCompiles('SELECT * FROM accounts', $query);
    }

    public function testLimit()
    {
        $query = $this->createSelect()->from('accounts')->limit(0, 0);
        $this->assertCompiles('SELECT * FROM accounts', $query);

        $query->limit(10);
        $this->assertCompiles('SELECT * FROM accounts LIMIT 10', $query);

        $query->limit(10, 0);
        $this->assertCompiles('SELECT * FROM accounts LIMIT 10', $query);

        $query->limit(0, 100);
        $this->assertCompiles('SELECT * FROM accounts OFFSET 100', $query);

        $query->limit(10, 100);
        $this->assertCompiles('SELECT * FROM accounts LIMIT 10 OFFSET 100', $query);

        $query->reset(SELECT::PART_LIMIT);
        $this->assertCompiles('SELECT * FROM accounts', $query);
    }

    public function testJoin()
    {
        // Tests only the HasJoin trait, Join builder is tested in a separate file
        $query = $this->createSelect()->from(['a' => 'accounts']);
        $query->columns(['t.*', 'a.name']);
        $query->join(['t' => 'transactions'], 't.account_id = a.account_id');
        $this->assertCompiles(
            'SELECT t.*, a.name FROM accounts AS a JOIN transactions AS t ON t.account_id = a.account_id',
            $query
        );

        $query->reset(Select::PART_COLUMNS);
        $query->reset(SELECT::PART_JOIN);
        $query->joinLeft(['t' => 'transactions'], 't.account_id = a.account_id');
        $this->assertCompiles(
            'SELECT * FROM accounts AS a LEFT JOIN transactions AS t ON t.account_id = a.account_id',
            $query
        );

        $query->reset(SELECT::PART_JOIN);
        $query->joinRight(['t' => 'transactions'], 't.account_id = a.account_id');
        $this->assertCompiles(
            'SELECT * FROM accounts AS a RIGHT JOIN transactions AS t ON t.account_id = a.account_id',
            $query
        );

        $query->reset(SELECT::PART_JOIN);
        $query->joinCross(['t' => 'transactions']);
        $this->assertCompiles('SELECT * FROM accounts AS a CROSS JOIN transactions AS t', $query);

        $query->reset(SELECT::PART_JOIN);
        $query->joinNatural(['t' => 'transactions']);
        $this->assertCompiles('SELECT * FROM accounts AS a NATURAL JOIN transactions AS t', $query);

        $query->reset(SELECT::PART_JOIN);
        $this->assertCompiles('SELECT * FROM accounts AS a', $query);
    }

    public function testWhere()
    {
        // Tests only the HasWhere trait, Condition/ConditionGroup builders are tested in a separate file
        $query = $this->createSelect()->from('accounts')->where('balance', '>', 1000);
        $this->assertCompiles('SELECT * FROM accounts WHERE (balance > 1000)', $query);
        $query->orWhere('balance', '<', 1000);
        $this->assertCompiles('SELECT * FROM accounts WHERE (balance > 1000) OR (balance < 1000)', $query);

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
        $this->assertCompiles('SELECT * FROM accounts', $query);
    }

    public function testHaving()
    {
        // Tests only the HasHaving trait, Condition/ConditionGroup builders are tested in a separate file
        $query = $this->createSelect()->from('accounts')->having('balance', '>', 1000);
        $this->assertCompiles('SELECT * FROM accounts HAVING (balance > 1000)', $query);
        $query->orHaving('balance', '<', 1000);
        $this->assertCompiles('SELECT * FROM accounts HAVING (balance > 1000) OR (balance < 1000)', $query);

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
        $this->assertCompiles('SELECT * FROM accounts', $query);
    }

    public function testUnion()
    {
        $query = $this->createSelect()->from('accounts');

        $query->union($this->createSelect()->from('accs'));
        $this->assertCompiles('SELECT * FROM accounts UNION SELECT * FROM accs', $query);

        $query->union($this->createSelect()->from('a'), true);
        $this->assertCompiles('SELECT * FROM accounts UNION SELECT * FROM accs UNION ALL SELECT * FROM a', $query);

        $query->reset(SELECT::PART_UNION);
        $this->assertCompiles('SELECT * FROM accounts', $query);
    }

    public function testReset()
    {
        $query = $this->createSelect();
        $this->assertNotEmpty($query->toString());

        $query->reset();
        $this->assertCompiles('SELECT *', $query);
    }

    public function testGetPart()
    {
        $query = $this->createSelect();
        $this->assertInstanceOf(Distinct::class, $query->getPart('distinct'));
        $this->assertInstanceOf(Columns::class, $query->getPart('columns'));
        $this->assertInstanceOf(From::class, $query->getPart('from'));
        $this->assertInstanceOf(Join::class, $query->getPart('join'));
        $this->assertInstanceOf(Where::class, $query->getPart('where'));
        $this->assertInstanceOf(Group::class, $query->getPart('group'));
        $this->assertInstanceOf(Having::class, $query->getPart('having'));
        $this->assertInstanceOf(Order::class, $query->getPart('order'));
        $this->assertInstanceOf(Limit::class, $query->getPart('limit'));
        $this->assertInstanceOf(Union::class, $query->getPart('union'));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionOnUndefinedPart()
    {
        $this->createSelect()->getPart('notexists');
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

        $this->assertCompiles($expectedQuery, $actualQuery);
    }
}
