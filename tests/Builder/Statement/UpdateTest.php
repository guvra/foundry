<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder\Statement;

use Foundry\Builder\Clause\Join;
use Foundry\Builder\Clause\Update\Limit;
use Foundry\Builder\Clause\Update\Table;
use Foundry\Builder\Clause\Update\Values;
use Foundry\Builder\Clause\Where;
use Foundry\Tests\TestCase;

/**
 * Test the UPDATE query builder.
 */
class UpdateTest extends TestCase
{
    public function testUpdate()
    {
        $query = $this->createUpdate()
            ->table('accounts')
            ->values(['name' => 'Account 5'])
            ->where('name', '=', 'Account 1')
            ->limit(1);

        $quoteOldValue = $this->connection->quote('Account 1');
        $quotedNewValue = $this->connection->quote('Account 5');

        $this->assertEquals("UPDATE accounts SET name = $quotedNewValue WHERE (name = $quoteOldValue) LIMIT 1", $query->toString());
    }

    public function testReset()
    {
        $query = $this->createUpdate()->table('accounts')->values(['balance' => 0]);
        $this->assertNotEmpty($query->toString());

        $query->reset();
        $this->assertEmpty($query->toString());
    }

    public function testGetPart()
    {
        $query = $this->createUpdate();
        $this->assertInstanceOf(Table::class, $query->getPart('table'));
        $this->assertInstanceOf(Join::class, $query->getPart('join'));
        $this->assertInstanceOf(Where::class, $query->getPart('where'));
        $this->assertInstanceOf(Values::class, $query->getPart('values'));
        $this->assertInstanceOf(Limit::class, $query->getPart('limit'));
    }
}
