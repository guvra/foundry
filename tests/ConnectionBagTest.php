<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Tests;

use Guvra\ConnectionBag;

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
        $bag->addConnection($this->connection, 'Guvra');
        $this->assertInstanceOf('Guvra\ConnectionInterface', $bag->getConnection('Guvra'));
    }
}
