<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder;

use Foundry\Expression;
use Foundry\Tests\TestCase;

/**
 * Test the expression object.
 */
class ExpressionTest extends TestCase
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
