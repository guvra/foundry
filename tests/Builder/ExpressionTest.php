<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder;

use Guvra\Builder\Expression;
use Tests\AbstractTestCase;

/**
 * Test the expression object.
 */
class ExpressionTest extends AbstractTestCase
{
    public function testExpressionWithValue()
    {
        $expression = new Expression('MAX(amount)');
        $this->assertEquals('MAX(amount)', $expression);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionWhenEmptyValue()
    {
        new Expression('');
    }
}
