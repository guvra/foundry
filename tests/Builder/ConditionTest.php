<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder;

use Foundry\Builder\ConditionGroup;
use Foundry\Builder\Statement\Select;
use Foundry\Expression;
use Foundry\Parameter;
use Foundry\Tests\TestCase;

/**
 * Test the condition builder.
 */
class ConditionTest extends TestCase
{
    public function testBasicOperator()
    {
        $condition = $this->createCondition('amount', '>', 1000);
        $this->assertCompiles('amount > 1000', $condition);
    }

    public function testRawCondition()
    {
        $condition = $this->createCondition('amount > 1000');
        $this->assertCompiles('amount > 1000', $condition);
    }

    public function testExistsOperator()
    {
        $condition = $this->createCondition(function (Select $subQuery) {
            $subQuery->from('accounts')
                ->columns('account_id')
                ->where('account_id', '=', 1);
        }, 'exists');

        $this->assertCompiles('EXISTS (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition);
    }

    public function testNotExistsOperator()
    {
        $condition = $this->createCondition(function (Select $subQuery) {
            $this->prepareSubQuery($subQuery);
        }, 'not exists');

        $this->assertCompiles('NOT EXISTS (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition);
    }

    public function testNullOperator()
    {
        $condition = $this->createCondition('description', 'null');
        $this->assertCompiles('description IS NULL', $condition);

        $condition = $this->createCondition('description', 'is null');
        $this->assertCompiles('description IS NULL', $condition);
    }

    public function testNotNullOperator()
    {
        $condition = $this->createCondition('description', 'not null');
        $this->assertCompiles('description IS NOT NULL', $condition);

        $condition = $this->createCondition('description', 'is not null');
        $this->assertCompiles('description IS NOT NULL', $condition);
    }

    public function testBetweenOperator()
    {
        $condition = $this->createCondition('amount', 'between', [1, 100]);
        $this->assertCompiles('amount BETWEEN 1 AND 100', $condition);
    }

    public function testNotBetweenOperator()
    {
        $condition = $this->createCondition('amount', 'not between', [1, 100]);
        $this->assertCompiles('amount NOT BETWEEN 1 AND 100', $condition);
    }

    public function testInOperator()
    {
        $condition = $this->createCondition('amount', 'in', [1, 2, 3]);
        $this->assertCompiles('amount IN (1,2,3)', $condition);

        $subQuery = $this->connection->select();
        $this->prepareSubQuery($subQuery);
        $condition = $this->createCondition('amount', 'in', $subQuery);
        $this->assertCompiles('amount IN (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition);
    }

    public function testNotInOperator()
    {
        $condition = $this->createCondition('amount', 'not in', [1, 2, 3]);
        $this->assertCompiles('amount NOT IN (1,2,3)', $condition);

        $subQuery = $this->connection->select();
        $this->prepareSubQuery($subQuery);
        $condition = $this->createCondition('amount', 'not in', $subQuery);
        $this->assertCompiles('amount NOT IN (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition);
    }

    public function testConditionGroup()
    {
        $condition = $this->createCondition(function (ConditionGroup $conditionGroup) {
            $conditionGroup->where('amount', '>', 1000);
        });

        $this->assertCompiles('(amount > 1000)', $condition);
    }

    public function testIntValue()
    {
        $condition = $this->createCondition('amount', '<', 1000);
        $this->assertCompiles('amount < 1000', $condition);
    }

    public function testFloatValue()
    {
        $condition = $this->createCondition('amount', '<', 9.99);
        $this->assertCompiles("amount < 9.99", $condition);
    }

    public function testStringValue()
    {
        $condition = $this->createCondition('name', 'like', '%something%');
        $quotedValue = $this->connection->quote('%something%');
        $this->assertCompiles("name LIKE $quotedValue", $condition);
    }

    public function testNullValue()
    {
        // Makes no sense but valid SQL syntax
        $condition = $this->createCondition('name', '=', null);
        $this->assertCompiles('name = null', $condition);
    }

    public function testSubQueryComparison()
    {
        // Sub query in $column
        $subQuery = $this->connection->select();
        $this->prepareSubQuery($subQuery);

        $condition = $this->createCondition($subQuery, '=', new Expression('account_id'));
        $this->assertCompiles('(SELECT account_id FROM accounts WHERE (account_id = 1)) = account_id', $condition);

        // Sub query in $value
        $condition = $this->createCondition('account_id', '=', $subQuery);
        $this->assertCompiles('account_id = (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition);
    }

    public function testSubQueryComparisonWithCallback()
    {
        // Callback in $column
        $condition = $this->createCondition(function (Select $subQuery) {
            $this->prepareSubQuery($subQuery);
        }, '=', new Expression('account_id'));

        $this->assertCompiles('(SELECT account_id FROM accounts WHERE (account_id = 1)) = account_id', $condition);

        // Callback in $value
        $condition = $this->createCondition('account_id', '=', function (Select $subQuery) {
            $this->prepareSubQuery($subQuery);
        });

        $this->assertCompiles('account_id = (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition);
    }

    public function testWithExpression()
    {
        $condition = $this->createCondition(50, '=', new Expression('amount'));
        $this->assertCompiles('50 = amount', $condition);
    }

    public function testWithParameter()
    {
        $condition = $this->createCondition('name', '=', new Parameter);
        $this->assertCompiles('name = ?', $condition);

        $condition = $this->createCondition('name', '=', new Parameter('name'));
        $this->assertCompiles('name = :name', $condition);

        $condition = $this->createCondition('name', '=', new Parameter(':name'));
        $this->assertCompiles('name = :name', $condition);
    }

    public function testReset()
    {
        $condition = $this->createCondition('amount', '=', 50);
        $this->assertNotEmpty($condition->toString());

        $condition->reset();
        $this->assertEmpty($condition->toString());
    }

    /**
     * @param Select $subQuery
     */
    private function prepareSubQuery(Select $subQuery)
    {
        $subQuery->from('accounts')
            ->columns('account_id')
            ->where('account_id', '=', 1);
    }
}
