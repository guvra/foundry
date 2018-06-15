<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Clause;

use Tests\AbstractTestCase;

/**
 * Test the HAVING builder.
 */
class HavingTest extends AbstractTestCase
{
    public function testWhere()
    {
        // A single test is sufficient, this clause is a small wrapper of the condition group builder
        $having = $this->createHaving();
        $having->where('amount', '>', 1000)
            ->orWhere('amount', '<', 1000)
            ->where('description', 'is null');

        $this->assertEquals('HAVING (amount > 1000) OR (amount < 1000) AND (description IS NULL)', $having->toString());
    }
}
