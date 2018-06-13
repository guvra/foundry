<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests;

use Guvra\ConnectionBag;
use Guvra\ConnectionInterface;

/**
 * Test the connection bag.
 */
class ConnectionBagTest extends AbstractTestCase
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
