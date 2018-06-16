<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder\Clause;

use Foundry\Tests\TestCase;

/**
 * Test the HAVING builder.
 */
class HavingTest extends TestCase
{
    public function testWhere()
    {
        // A single test is sufficient, this clause is a small wrapper of the condition group builder
        $having = $this->createHaving();
        $having->where('amount', '>', 1000)
            ->orWhere('amount', '<', 1000)
            ->where('description', 'is null');

        $this->assertCompiles('HAVING (amount > 1000) OR (amount < 1000) AND (description IS NULL)', $having);
    }
}
