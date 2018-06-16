<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder\Clause;

use Foundry\Builder\Clause\Join;
use Foundry\Builder\ConditionGroup;
use Foundry\Expression;
use Foundry\Tests\TestCase;

/**
 * Test the JOIN builder.
 */
class JoinTest extends TestCase
{
    public function testInnerJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_INNER, 'accounts', 'accounts.account_id = transactions.account_id');
        $this->assertCompiles('JOIN accounts ON accounts.account_id = transactions.account_id', $joinGroup);

        $joinGroup->addJoin(Join::TYPE_INNER, ['c' => 'categories']);
        $this->assertCompiles(
            'JOIN accounts ON accounts.account_id = transactions.account_id JOIN categories AS c',
            $joinGroup
        );
    }

    public function testLeftJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_LEFT, 'accounts', 'accounts.account_id = transactions.account_id');
        $this->assertCompiles('LEFT JOIN accounts ON accounts.account_id = transactions.account_id', $joinGroup);

        $joinGroup->addJoin(Join::TYPE_LEFT, ['c' => 'categories']);
        $this->assertCompiles(
            'LEFT JOIN accounts ON accounts.account_id = transactions.account_id LEFT JOIN categories AS c',
            $joinGroup
        );
    }

    public function testRightJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_RIGHT, 'accounts', 'accounts.account_id = transactions.account_id');
        $this->assertCompiles('RIGHT JOIN accounts ON accounts.account_id = transactions.account_id', $joinGroup);

        $joinGroup->addJoin(Join::TYPE_RIGHT, ['c' => 'categories']);
        $this->assertCompiles(
            'RIGHT JOIN accounts ON accounts.account_id = transactions.account_id RIGHT JOIN categories AS c',
            $joinGroup
        );
    }

    public function testCrossJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_CROSS, 'accounts');
        $this->assertCompiles('CROSS JOIN accounts', $joinGroup);

        $joinGroup->addJoin(Join::TYPE_CROSS, ['c' => 'categories']);
        $this->assertCompiles('CROSS JOIN accounts CROSS JOIN categories AS c', $joinGroup);
    }

    public function testNaturalJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_NATURAL, 'accounts');
        $this->assertCompiles('NATURAL JOIN accounts', $joinGroup);

        $joinGroup->addJoin(Join::TYPE_NATURAL, ['c' => 'categories']);
        $this->assertCompiles('NATURAL JOIN accounts NATURAL JOIN categories AS c', $joinGroup);
    }

    public function testJoinWithCondition()
    {
        $joinGroup = $this->createJoin();
        $expression = new Expression('transactions.account_id');
        $joinGroup->addJoin(Join::TYPE_INNER, 'accounts', 'accounts.account_id', '=', $expression);
        $this->assertCompiles('JOIN accounts ON accounts.account_id = transactions.account_id', $joinGroup);
    }

    public function testJoinWithConditionGroup()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_INNER, 'accounts', function (ConditionGroup $group) {
            $group->where('accounts.transaction_id = transactions.transaction_id')
                ->where('accounts.balance > 0');
        });

        $this->assertCompiles(
            'JOIN accounts ON (accounts.transaction_id = transactions.transaction_id) AND (accounts.balance > 0)',
            $joinGroup
        );
    }

    public function testReset()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_INNER, 'accounts', 'accounts.account_id = transactions.account_id');
        $this->assertNotEmpty($joinGroup->toString());

        $joinGroup->reset();
        $this->assertEmpty($joinGroup->toString());
    }
}
