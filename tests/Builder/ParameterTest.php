<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder;

use Foundry\Parameter;
use Foundry\Tests\TestCase;

/**
 * Test the parameter object.
 */
class ParameterTest extends TestCase
{
    public function testParameterWithValue()
    {
        $parameter = new Parameter('name');
        $this->assertEquals(':name', $parameter->toString());

        $parameter = new Parameter(':name');
        $this->assertEquals(':name', $parameter->toString());
    }

    public function testParameterWithNoValue()
    {
        $parameter = new Parameter;
        $this->assertEquals('?', $parameter->toString());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionWhenEmptyValue()
    {
        new Parameter('');
    }
}
