<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder;

use Guvra\Expression;
use Tests\AbstractTestCase;

/**
 * Test the expression object.
 */
class ExpressionTest extends AbstractTestCase
{
    public function testExpressionWithValue()
    {
        $expression = new Expression('MAX(amount)');
        $this->assertEquals('MAX(amount)', $expression->toString());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExceptionWhenEmptyValue()
    {
        new Expression('');
    }
}
