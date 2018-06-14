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

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testEmptyDelete()
    {
        $this->createDelete()->toString();
    }
}
