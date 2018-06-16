<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests;

use Foundry\ConnectionBag;
use Foundry\ConnectionInterface;

/**
 * Test the connection bag.
 */
class ConnectionBagTest extends TestCase
{
    /**
     * Test the connection bag.
     */
    public function testBag()
    {
        $bag = new ConnectionBag;
        $bag->addConnection($this->connection, 'test');
        $this->assertTrue($bag->hasConnection('test'));
        $this->assertInstanceOf(ConnectionInterface::class, $bag->getConnection('test'));

        $bag->removeConnection('test');
        $this->assertFalse($bag->hasConnection('test'));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionOnUnknownBag()
    {
        $bag = new ConnectionBag;
        $bag->getConnection('notexists');
    }
}
