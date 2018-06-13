<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder;

use Guvra\Builder\Parameter;
use Tests\AbstractTestCase;

/**
 * Test the parameter object.
 */
class ParameterTest extends AbstractTestCase
{
    public function testParameterWithValue()
    {
        $parameter = new Parameter('name');
        $this->assertEquals(':name', $parameter);

        $parameter = new Parameter(':name');
        $this->assertEquals(':name', $parameter);
    }

    public function testParameterWithNoValue()
    {
        $parameter = new Parameter;
        $this->assertEquals('?', $parameter);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionWhenEmptyValue()
    {
        new Parameter('');
    }
}
