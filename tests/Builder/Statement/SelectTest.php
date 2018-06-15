<?php
/**
 * PHP Query Builder.
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
        $query = $this->createSelect()->distinct(false);
        $this->assertEquals('SELECT * FROM accounts', $query->toString());

        $query->reset(Select::PART_DISTINCT);
        $query->distinct();
        $this->assertEquals('SELECT DISTINCT * FROM accounts', $query->toString());

        $query->reset(Select::PART_DISTINCT);
        $query->distinct(true);
        $this->assertEquals('SELECT DISTINCT * FROM accounts', $query->toString());
    }

    public function testFrom()
    {
        $query = $this->createSelect();
        $this->assertEquals('SELECT * FROM accounts', $query->toString());

        $query->from(['c' => 'categories']);
        $this->assertEquals('SELECT * FROM categories AS c', $query->toString());

        $query->from(['accounts', 'transactions']);
        $this->assertEquals('SELECT * FROM accounts, transactions', $query->toString());

        $query->from(['a' => 'accounts', 't' => 'transactions']);
        $this->assertEquals('SELECT * FROM accounts AS a, transactions AS t', $query->toString());

        $query->reset(Select::PART_FROM);
        $this->assertEquals('SELECT *', $query->toString());
    }

    public function testColumns()
    {
        $query = $this->createSelect();
        $this->assertEquals('SELECT * FROM accounts', $query->toString());

        $query->columns('name');
        $this->assertEquals('SELECT name FROM accounts', $query->toString());

        $query->columns('accounts.balance');
        $this->assertEquals('SELECT accounts.balance FROM accounts', $query->toString());

        $query->columns(['accounts.name', 'accounts.balance']);
        $this->assertEquals('SELECT accounts.name, accounts.balance FROM accounts', $query->toString());

        $query->columns(['n' => 'accounts.name', 'b' => 'accounts.balance']);
        $this->assertEquals('SELECT accounts.name AS n, accounts.balance AS b FROM accounts', $query->toString());

        $query->columns(['min' => 'MIN(balance)']);
        $this->assertEquals('SELECT MIN(balance) AS min FROM accounts', $query->toString());

        $query->reset(Select::PART_COLUMNS);
        $this->assertEquals('SELECT * FROM accounts', $query->toString());

        $query->reset(Select::PART_COLUMNS);
        $query->reset(Select::PART_FROM);
        $query->columns(1);
        $this->assertEquals('SELECT 1', $query->toString());
    }

    public function testSubQueryColumn()
    {
        $query = $this->createSelect();
        $query->columns(['max' => $this->createSelect()->columns('MAX(balance)')]);
        $this->assertEquals('SELECT (SELECT MAX(balance) FROM accounts) AS max FROM accounts', $query->toString());
    }

    public function testGroupBy()
    {
        $query = $this->createSelect();
        $query->from('transactions')->group('account_id');
        $this->assertEquals('SELECT * FROM transactions GROUP BY account_id', $query->toString());

        $query->group('category_id');
        $this->assertEquals('SELECT * FROM transactions GROUP BY category_id', $query->toString());

        $query->reset(Select::PART_GROUP);
        $query->group(['category_id', 'account_id']);
        $this->assertEquals('SELECT * FROM transactions GROUP BY category_id, account_id', $query->toString());

        $query->reset(Select::PART_GROUP);
        $this->assertEquals('SELECT * FROM transactions', $query->toString());
    }

    public function testOrderBy()
    {
        $query = $this->createSelect()->order('balance DESC');
        $this->assertEquals('SELECT * FROM accounts ORDER BY balance DESC', $query->toString());

        $query->order('name ASC');
        $this->assertEquals('SELECT * FROM accounts ORDER BY name ASC', $query->toString());

        $query->order(['balance DESC', 'name ASC']);
        $this->assertEquals('SELECT * FROM accounts ORDER BY balance DESC, name ASC', $query->toString());

        $query->reset(SELECT::PART_ORDER);
        $this->assertEquals('SELECT * FROM accounts', $query->toString());
    }

    public function testLimit()
    {
        $query = $this->createSelect()->limit(0, 0);
        $this->assertEquals('SELECT * FROM accounts', $query->toString());

        $query->limit(10);
        $this->assertEquals('SELECT * FROM accounts LIMIT 10', $query->toString());

        $query->limit(10, 0);
        $this->assertEquals('SELECT * FROM accounts LIMIT 10', $query->toString());

        $query->limit(0, 100);
        $this->assertEquals('SELECT * FROM accounts OFFSET 100', $query->toString());

        $query->limit(10, 100);
        $this->assertEquals('SELECT * FROM accounts LIMIT 10 OFFSET 100', $query->toString());

        $query->reset(SELECT::PART_LIMIT);
        $this->assertEquals('SELECT * FROM accounts', $query->toString());
    }

    public function testJoin()
    {
        // Tests only the HasJoin trait, Join builder is tested in a separate file
        $query = $this->createSelect()->join('transactions', 'transactions.account_id = accounts.account_id');
        $this->assertEquals('SELECT * FROM accounts JOIN transactions ON transactions.account_id = accounts.account_id', $query->toString());

        $query->reset(SELECT::PART_JOIN);
        $query->joinLeft('transactions', 'transactions.account_id = accounts.account_id');

        $query->joinRight('transactions', 'transactions.account_id = accounts.account_id');
        $query->joinCross('transactions');
        $query->joinNatural('transactions');

        $query->reset(SELECT::PART_JOIN);
        $this->assertEquals('SELECT * FROM accounts', $query->toString());
    }

    public function testWhere()
    {
        // Tests only the HasWhere trait, Condition/ConditionGroup builders are tested in a separate file
        $query = $this->createSelect()->where('balance', '>', 1000);
        $this->assertEquals('SELECT * FROM accounts WHERE (balance > 1000)', $query->toString());
        $query->orWhere('balance', '<', 1000);
        $this->assertEquals('SELECT * FROM accounts WHERE (balance > 1000) OR (balance < 1000)', $query->toString());

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
        $this->assertEquals('SELECT * FROM accounts', $query->toString());
    }

    public function testHaving()
    {
        // Tests only the HasHaving trait, Condition/ConditionGroup builders are tested in a separate file
        $query = $this->createSelect()->having('balance', '>', 1000);
        $this->assertEquals('SELECT * FROM accounts HAVING (balance > 1000)', $query->toString());
        $query->orHaving('balance', '<', 1000);
        $this->assertEquals('SELECT * FROM accounts HAVING (balance > 1000) OR (balance < 1000)', $query->toString());

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
        $this->assertEquals('SELECT * FROM accounts', $query->toString());
    }

    public function testUnion()
    {
        $query = $this->createSelect();

        $query->union($this->createSelect()->reset(Select::PART_FROM)->from('accs'));
        $this->assertEquals('SELECT * FROM accounts UNION SELECT * FROM accs', $query->toString());

        $query->union($this->createSelect()->reset(Select::PART_FROM)->from('a'), true);
        $this->assertEquals('SELECT * FROM accounts UNION SELECT * FROM accs UNION ALL SELECT * FROM a', $query->toString());

        $query->reset(SELECT::PART_UNION);
        $this->assertEquals('SELECT * FROM accounts', $query->toString());
    }

    public function testReset()
    {
        $query = $this->createSelect();
        $this->assertNotEmpty($query->toString());

        $query->reset();
        $this->assertEquals('SELECT *', $query->toString());
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
     * @expectedException  \UnexpectedValueException
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

        $this->assertEquals($expectedQuery, $actualQuery->toString());
    }
}
