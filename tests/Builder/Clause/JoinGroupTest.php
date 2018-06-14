<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Clause;

use Guvra\Builder\Expression;
use Tests\AbstractTestCase;

/**
 * Test the join group builder.
 */
class JoinGroupTest extends AbstractTestCase
{
    public function testInnerJoin()
    {
        $joinGroup = $this->createJoinGroup();
        $condition = $this->createCondition('transactions.account_id', '=', new Expression('accounts.account_id'));
        $joinGroup->join('accounts', $condition);
        $this->assertEquals('JOIN accounts ON transactions.account_id = accounts.account_id', $joinGroup->toString());

        $joinGroup->join(['c' => 'categories']);
        $this->assertEquals('JOIN accounts ON transactions.account_id = accounts.account_id JOIN categories AS c', $joinGroup->toString());
    }

    public function testLeftJoin()
    {
        $joinGroup = $this->createJoinGroup();
        $condition = $this->createCondition('transactions.account_id', '=', new Expression('accounts.account_id'));
        $joinGroup->joinLeft('accounts', $condition);
        $this->assertEquals('LEFT JOIN accounts ON transactions.account_id = accounts.account_id', $joinGroup->toString());

        $joinGroup->joinLeft(['c' => 'categories']);
        $this->assertEquals('LEFT JOIN accounts ON transactions.account_id = accounts.account_id LEFT JOIN categories AS c', $joinGroup->toString());
    }

    public function testRightJoin()
    {
        $joinGroup = $this->createJoinGroup();
        $condition = $this->createCondition('transactions.account_id', '=', new Expression('accounts.account_id'));
        $joinGroup->joinRight('accounts', $condition);
        $this->assertEquals('RIGHT JOIN accounts ON transactions.account_id = accounts.account_id', $joinGroup->toString());

        $joinGroup->joinRight(['c' => 'categories']);
        $this->assertEquals('RIGHT JOIN accounts ON transactions.account_id = accounts.account_id RIGHT JOIN categories AS c', $joinGroup->toString());
    }

    public function testCrossJoin()
    {
        $joinGroup = $this->createJoinGroup();
        $joinGroup->joinCross('accounts');
        $this->assertEquals('CROSS JOIN accounts', $joinGroup->toString());

        $joinGroup->joinCross(['c' => 'categories']);
        $this->assertEquals('CROSS JOIN accounts CROSS JOIN categories AS c', $joinGroup->toString());
    }

    public function testNaturalJoin()
    {
        $joinGroup = $this->createJoinGroup();
        $joinGroup->joinNatural('accounts');
        $this->assertEquals('NATURAL JOIN accounts', $joinGroup->toString());

        $joinGroup->joinNatural(['c' => 'categories']);
        $this->assertEquals('NATURAL JOIN accounts NATURAL JOIN categories AS c', $joinGroup->toString());
    }

    public function testJoin()
    {
        $joinGroup = $this->createJoinGroup();
        $joinGroup->addJoin($this->createJoin('inner', 'accounts'));
        $joinGroup->addJoin($this->createJoin('inner', 'categories'));
        $this->assertEquals('JOIN accounts JOIN categories', $joinGroup->toString());
    }
}
