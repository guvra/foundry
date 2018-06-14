<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Statement;

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

    public function testInsertIgnore()
    {
        $query = $this->createInsert()
            ->ignore()
            ->into('accounts')
            ->columns(['balance'])
            ->values([500]);

        $this->assertEquals('INSERT OR IGNORE INTO accounts (balance) VALUES (500)', $query->toString());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionOnEmptyTable()
    {
        $query = $this->createInsert()
            ->columns(['name', 'balance'])
            ->values(['Account 3', 500]);

        $query->toString();
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionOnEmptyColumns()
    {
        $query = $this->createInsert()
            ->into('accounts')
            ->values(['Account 3', 500]);

        $query->toString();
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionOnEmptyValues()
    {
        $query = $this->createInsert()
            ->into('accounts')
            ->columns(['name', 'balance']);

        $query->toString();
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionOnWrongValuesFormat()
    {
        $query = $this->createInsert()
            ->into('table')
            ->columns(['col1', 'col2'])
            ->values([[0, 10, 20], 1]);

        $query->toString();
    }
}
