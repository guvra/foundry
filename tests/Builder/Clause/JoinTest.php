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
 * Test the join builder.
 */
class JoinTest extends AbstractTestCase
{
    public function testInnerJoin()
    {
        $join = $this->createJoin('inner', 'accounts');
        $this->assertEquals('JOIN accounts', $join->toString());

        $condition = $this->createCondition('transactions.account_id', '=', new Expression('accounts.account_id'));
        $join = $this->createJoin('inner', 'accounts', $condition);
        $this->assertEquals('JOIN accounts ON transactions.account_id = accounts.account_id', $join->toString());

        $condition = $this->createCondition('store_id', '=', 0);
        $join = $this->createJoin('inner', 'accounts', $condition);
        $this->assertEquals('JOIN accounts ON store_id = 0', $join->toString());
    }

    public function testLeftJoin()
    {
        $join = $this->createJoin('left', 'accounts');
        $this->assertEquals('LEFT JOIN accounts', $join->toString());

        $condition = $this->createCondition('transactions.account_id', '=', new Expression('accounts.account_id'));
        $join = $this->createJoin('left', 'accounts', $condition);
        $this->assertEquals('LEFT JOIN accounts ON transactions.account_id = accounts.account_id', $join->toString());

        $condition = $this->createCondition('store_id', '=', 0);
        $join = $this->createJoin('left', 'accounts', $condition);
        $this->assertEquals('LEFT JOIN accounts ON store_id = 0', $join->toString());
    }

    public function testRightJoin()
    {
        $join = $this->createJoin('right', 'accounts');
        $this->assertEquals('RIGHT JOIN accounts', $join->toString());

        $condition = $this->createCondition('transactions.account_id', '=', new Expression('accounts.account_id'));
        $join = $this->createJoin('right', 'accounts', $condition);
        $this->assertEquals('RIGHT JOIN accounts ON transactions.account_id = accounts.account_id', $join->toString());

        $condition = $this->createCondition('store_id', '=', 0);
        $join = $this->createJoin('right', 'accounts', $condition);
        $this->assertEquals('RIGHT JOIN accounts ON store_id = 0', $join->toString());
    }

    public function testCrossJoin()
    {
        $join = $this->createJoin('cross', 'accounts');
        $this->assertEquals('CROSS JOIN accounts', $join->toString());
    }

    public function testNaturalJoin()
    {
        $join = $this->createJoin('natural', 'accounts');
        $this->assertEquals('NATURAL JOIN accounts', $join->toString());
    }
}
