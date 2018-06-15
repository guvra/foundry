<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Clause;

use Guvra\Builder\Clause\Join;
use Guvra\Builder\ConditionGroup;
use Guvra\Builder\Expression;
use Tests\AbstractTestCase;

/**
 * Test the JOIN builder.
 */
class JoinTest extends AbstractTestCase
{
    public function testInnerJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_INNER, 'accounts', 'accounts.account_id = transactions.account_id');
        $this->assertEquals('JOIN accounts ON accounts.account_id = transactions.account_id', $joinGroup->toString());

        $joinGroup->addJoin(Join::TYPE_INNER, ['c' => 'categories']);
        $this->assertEquals('JOIN accounts ON accounts.account_id = transactions.account_id JOIN categories AS c', $joinGroup->toString());
    }

    public function testLeftJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_LEFT, 'accounts', 'accounts.account_id = transactions.account_id');
        $this->assertEquals('LEFT JOIN accounts ON accounts.account_id = transactions.account_id', $joinGroup->toString());

        $joinGroup->addJoin(Join::TYPE_LEFT, ['c' => 'categories']);
        $this->assertEquals('LEFT JOIN accounts ON accounts.account_id = transactions.account_id LEFT JOIN categories AS c', $joinGroup->toString());
    }

    public function testRightJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_RIGHT, 'accounts', 'accounts.account_id = transactions.account_id');
        $this->assertEquals('RIGHT JOIN accounts ON accounts.account_id = transactions.account_id', $joinGroup->toString());

        $joinGroup->addJoin(Join::TYPE_RIGHT, ['c' => 'categories']);
        $this->assertEquals('RIGHT JOIN accounts ON accounts.account_id = transactions.account_id RIGHT JOIN categories AS c', $joinGroup->toString());
    }

    public function testCrossJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_CROSS, 'accounts');
        $this->assertEquals('CROSS JOIN accounts', $joinGroup->toString());

        $joinGroup->addJoin(Join::TYPE_CROSS, ['c' => 'categories']);
        $this->assertEquals('CROSS JOIN accounts CROSS JOIN categories AS c', $joinGroup->toString());
    }

    public function testNaturalJoin()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_NATURAL, 'accounts');
        $this->assertEquals('NATURAL JOIN accounts', $joinGroup->toString());

        $joinGroup->addJoin(Join::TYPE_NATURAL, ['c' => 'categories']);
        $this->assertEquals('NATURAL JOIN accounts NATURAL JOIN categories AS c', $joinGroup->toString());
    }

    public function testJoinWithCondition()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_INNER, 'accounts', 'accounts.account_id', '=', new Expression('transactions.account_id'));
        $this->assertEquals('JOIN accounts ON accounts.account_id = transactions.account_id', $joinGroup->toString());
    }

    public function testJoinWithConditionGroup()
    {
        $joinGroup = $this->createJoin();
        $joinGroup->addJoin(Join::TYPE_INNER, 'accounts', function (ConditionGroup $group) {
            $group->where('accounts.transaction_id = transactions.transaction_id')
                ->where('accounts.balance > 0');
        });

        $this->assertEquals('JOIN accounts ON (accounts.transaction_id = transactions.transaction_id) AND (accounts.balance > 0)', $joinGroup->toString());
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
