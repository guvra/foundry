<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Statement;

use Guvra\Builder\Clause\Delete\Limit;
use Guvra\Builder\Clause\Delete\Table;
use Guvra\Builder\Clause\Join;
use Guvra\Builder\Clause\Where;
use Tests\AbstractTestCase;

/**
 * Test the DELETE query builder.
 */
class DeleteTest extends AbstractTestCase
{
    public function testDelete()
    {
        $query = $this->createDelete()
            ->from('accounts')
            ->where('name', '=', 'Account 1')
            ->limit(1);

        $quotedValue = $this->connection->quote('Account 1');

        $this->assertEquals("DELETE FROM accounts WHERE (name = $quotedValue) LIMIT 1", $query->toString());
    }

    public function testReset()
    {
        $query = $this->createDelete()->from('accounts');
        $this->assertNotEmpty($query->toString());

        $query->reset();
        $this->assertEmpty($query->toString());
    }

    public function testGetPart()
    {
        $query = $this->createDelete();
        $this->assertInstanceOf(Table::class, $query->getPart('table'));
        $this->assertInstanceOf(Join::class, $query->getPart('join'));
        $this->assertInstanceOf(Where::class, $query->getPart('where'));
        $this->assertInstanceOf(Limit::class, $query->getPart('limit'));
    }
}
