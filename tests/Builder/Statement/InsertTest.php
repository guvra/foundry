<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Statement;

use Guvra\Builder\Clause\Insert\Columns;
use Guvra\Builder\Clause\Insert\Ignore;
use Guvra\Builder\Clause\Insert\Table;
use Guvra\Builder\Clause\Insert\Values;
use Tests\AbstractTestCase;

/**
 * Test the INSERT query builder.
 */
class InsertTest extends AbstractTestCase
{
    public function testInsertSingle()
    {
        $query = $this->createInsert()
            ->into('accounts')
            ->columns(['name', 'balance'])
            ->values(['Account 3', 500]);

        $expectedStringValue = $this->connection->quote('Account 3');

        $this->assertEquals("INSERT INTO accounts (name, balance) VALUES ($expectedStringValue,500)", $query->toString());
    }

    public function testInsertMultiple()
    {
        $query = $this->createInsert()
            ->into('table')
            ->columns(['col1', 'col2'])
            ->values([[0, 10, 20], [30, 40, 50]]);

        $this->assertEquals('INSERT INTO table (col1, col2) VALUES (0,10,20),(30,40,50)', $query->toString());
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

        $this->assertEquals('INSERT OR IGNORE INTO accounts (balance) VALUES (500)', $query->toString());
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
}
