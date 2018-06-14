<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Clause;

use Guvra\Builder\Clause\ConditionGroup;
use Guvra\Builder\Expression;
use Guvra\Builder\Parameter;
use Guvra\Builder\Statement\Select;
use Tests\AbstractTestCase;

/**
 * Test the condition builder.
 */
class ConditionTest extends AbstractTestCase
{
    public function testBasicOperator()
    {
        $condition = $this->createCondition('amount', '>', 1000);
        $this->assertEquals('amount > 1000', $condition->toString());
    }

    public function testRawCondition()
    {
        $condition = $this->createCondition('amount > 1000');
        $this->assertEquals('amount > 1000', $condition->toString());
    }

    public function testExistsOperator()
    {
        $condition = $this->createCondition(function (Select $subQuery) {
            $subQuery->from('accounts')
                ->columns('account_id')
                ->where('account_id', '=', 1);
        }, 'exists');

        $this->assertEquals('EXISTS (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition->toString());
    }

    public function testNotExistsOperator()
    {
        $condition = $this->createCondition(function (Select $subQuery) {
            $this->prepareSubQuery($subQuery);
        }, 'not exists');

        $this->assertEquals('NOT EXISTS (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition->toString());
    }

    public function testNullOperator()
    {
        $condition = $this->createCondition('description', 'null');
        $this->assertEquals('description IS NULL', $condition->toString());

        $condition = $this->createCondition('description', 'is null');
        $this->assertEquals('description IS NULL', $condition->toString());
    }

    public function testNotNullOperator()
    {
        $condition = $this->createCondition('description', 'not null');
        $this->assertEquals('description IS NOT NULL', $condition->toString());

        $condition = $this->createCondition('description', 'is not null');
        $this->assertEquals('description IS NOT NULL', $condition->toString());
    }

    public function testBetweenOperator()
    {
        $condition = $this->createCondition('amount', 'between', [1, 100]);
        $this->assertEquals('amount BETWEEN 1 AND 100', $condition->toString());
    }

    public function testNotBetweenOperator()
    {
        $condition = $this->createCondition('amount', 'not between', [1, 100]);
        $this->assertEquals('amount NOT BETWEEN 1 AND 100', $condition->toString());
    }

    public function testInOperator()
    {
        $condition = $this->createCondition('amount', 'in', [1, 2, 3]);
        $this->assertEquals('amount IN (1,2,3)', $condition->toString());

        $subQuery = $this->connection->select();
        $this->prepareSubQuery($subQuery);
        $condition = $this->createCondition('amount', 'in', $subQuery);
        $this->assertEquals('amount IN (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition->toString());
    }

    public function testNotInOperator()
    {
        $condition = $this->createCondition('amount', 'not in', [1, 2, 3]);
        $this->assertEquals('amount NOT IN (1,2,3)', $condition->toString());

        $subQuery = $this->connection->select();
        $this->prepareSubQuery($subQuery);
        $condition = $this->createCondition('amount', 'not in', $subQuery);
        $this->assertEquals('amount NOT IN (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition->toString());
    }

    public function testConditionGroup()
    {
        $condition = $this->createCondition(function (ConditionGroup $conditionGroup) {
            $conditionGroup->where('amount', '>', 1000);
        });

        $this->assertEquals('(amount > 1000)', $condition->toString());
    }

    public function testIntValue()
    {
        $condition = $this->createCondition('amount', '<', 1000);
        $this->assertEquals('amount < 1000', $condition->toString());
    }

    public function testFloatValue()
    {
        $condition = $this->createCondition('amount', '<', 9.99);
        $this->assertEquals("amount < 9.99", $condition->toString());
    }

    public function testStringValue()
    {
        $condition = $this->createCondition('name', 'like', '%something%');
        $quotedValue = $this->connection->quote('%something%');
        $this->assertEquals("name LIKE $quotedValue", $condition->toString());
    }

    public function testNullValue()
    {
        // Makes no sense but valid SQL syntax
        $condition = $this->createCondition('name', '=', null);
        $this->assertEquals('name = null', $condition->toString());
    }

    public function testSubQueryComparison()
    {
        // Sub query in $column
        $subQuery = $this->connection->select();
        $this->prepareSubQuery($subQuery);

        $condition = $this->createCondition($subQuery, '=', new Expression('account_id'));
        $this->assertEquals('(SELECT account_id FROM accounts WHERE (account_id = 1)) = account_id', $condition->toString());

        // Sub query in $value
        $condition = $this->createCondition('account_id', '=', $subQuery);
        $this->assertEquals('account_id = (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition->toString());
    }

    public function testSubQueryComparisonWithCallback()
    {
        // Callback in $column
        $condition = $this->createCondition(function (Select $subQuery) {
            $this->prepareSubQuery($subQuery);
        }, '=', new Expression('account_id'));

        $this->assertEquals('(SELECT account_id FROM accounts WHERE (account_id = 1)) = account_id', $condition->toString());

        // Callback in $value
        $condition = $this->createCondition('account_id', '=', function (Select $subQuery) {
            $this->prepareSubQuery($subQuery);
        });

        $this->assertEquals('account_id = (SELECT account_id FROM accounts WHERE (account_id = 1))', $condition->toString());
    }

    public function testWithExpression()
    {
        $condition = $this->createCondition(50, '=', new Expression('amount'));
        $this->assertEquals('50 = amount', $condition->toString());
    }

    public function testWithParameter()
    {
        $condition = $this->createCondition('name', '=', new Parameter);
        $this->assertEquals('name = ?', $condition->toString());

        $condition = $this->createCondition('name', '=', new Parameter('name'));
        $this->assertEquals('name = :name', $condition->toString());

        $condition = $this->createCondition('name', '=', new Parameter(':name'));
        $this->assertEquals('name = :name', $condition->toString());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionWhenColumnIsNull()
    {
        $condition = $this->createCondition(null);
        $condition->toString();
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
