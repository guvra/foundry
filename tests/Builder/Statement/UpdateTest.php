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
 * Test the UPDATE query builder.
 */
class UpdateTest extends AbstractTestCase
{
    public function testUpdate()
    {
        $query = $this->connection
            ->update()
            ->table('accounts')
            ->values(['name' => 'Account 5'])
            ->where('name', '=', 'Account 1')
            ->limit(1);

        $quoteOldValue = $this->connection->quote('Account 1');
        $quotedNewValue = $this->connection->quote('Account 5');

        $this->assertEquals("UPDATE accounts SET name = $quotedNewValue WHERE (name = $quoteOldValue) LIMIT 1", $query->toString());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionOnEmptyTable()
    {
        $query = $this->connection
            ->update()
            ->values(['name' => 'Account 5'])
            ->where('name', '=', 'Account 1');

        $query->toString();
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionOnEmptyValues()
    {
        $query = $this->connection
            ->update()
            ->table('accounts')
            ->where('name', '=', 'Account 1');

        $query->toString();
    }
}
