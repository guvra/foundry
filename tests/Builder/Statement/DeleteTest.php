<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder\Statement;

use Foundry\Builder\Clause\Delete\Limit;
use Foundry\Builder\Clause\Delete\Table;
use Foundry\Builder\Clause\Join;
use Foundry\Builder\Clause\Where;
use Foundry\Tests\TestCase;

/**
 * Test the DELETE query builder.
 */
class DeleteTest extends TestCase
{
    public function testDelete()
    {
        $query = $this->createDelete()
            ->from('accounts')
            ->where('name', '=', 'Account 1')
            ->limit(1);

        $quotedValue = $this->connection->quote('Account 1');

        $this->assertCompiles("DELETE FROM accounts WHERE (name = $quotedValue) LIMIT 1", $query);
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

    /**
     * @expectedException  \UnexpectedValueException
     */
    public function testExceptionOnUndefinedPart()
    {
        $this->createSelect()->getPart('notexists');
    }
}
