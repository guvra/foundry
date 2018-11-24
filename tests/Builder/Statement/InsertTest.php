<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder\Statement;

use Foundry\Builder\Clause\Insert\Columns;
use Foundry\Builder\Clause\Insert\Ignore;
use Foundry\Builder\Clause\Insert\Table;
use Foundry\Builder\Clause\Insert\Values;
use Foundry\Tests\TestCase;

/**
 * Test the INSERT query builder.
 */
class InsertTest extends TestCase
{
    public function testInsertSingle()
    {
        $query = $this->createInsert()
            ->into('accounts')
            ->columns(['name', 'balance'])
            ->values(['Account 3', 500]);

        $expectedStringValue = $this->connection->quote('Account 3');

        $this->assertCompiles("INSERT INTO accounts (name, balance) VALUES ($expectedStringValue,500)", $query);
    }

    public function testInsertMultiple()
    {
        $query = $this->createInsert()
            ->into('table')
            ->columns(['col1', 'col2'])
            ->values([[0, 10, 20], [30, 40, 50]]);

        $this->assertCompiles('INSERT INTO table (col1, col2) VALUES (0,10,20),(30,40,50)', $query);

        $query = $this->createInsert()
            ->into('table')
            ->columns(['col1', 'col2'])
            ->values([0, 10, 20])
            ->values([[30, 40, 50]]);

        $this->assertCompiles('INSERT INTO table (col1, col2) VALUES (0,10,20),(30,40,50)', $query);
    }

    public function testEmptyValues()
    {
        $query = $this->createInsert()
            ->values([]);

        $this->assertEmpty($query->toString());
    }

    public function testInsertIgnore()
    {
        $query = $this->createInsert()
            ->ignore()
            ->into('accounts')
            ->columns(['balance'])
            ->values([500]);

        $this->assertCompiles('INSERT OR IGNORE INTO accounts (balance) VALUES (500)', $query);
    }

    public function testReset()
    {
        $query = $this->createInsert()->into('accounts')->columns(['balance'])->values([500]);
        $this->assertNotEmpty($query->toString());

        $query->reset();
        $this->assertEmpty($query->toString());
    }

    public function testGetPart()
    {
        $query = $this->createInsert();
        $this->assertInstanceOf(Ignore::class, $query->getPart('ignore'));
        $this->assertInstanceOf(Table::class, $query->getPart('table'));
        $this->assertInstanceOf(Columns::class, $query->getPart('columns'));
        $this->assertInstanceOf(Values::class, $query->getPart('values'));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionOnUndefinedPart()
    {
        $this->createInsert()->getPart('notexists');
    }
}
