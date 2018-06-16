<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder\Clause;

use Foundry\Tests\TestCase;

/**
 * Test the WHERE builder.
 */
class WhereTest extends TestCase
{
    public function testWhere()
    {
        // A single test is sufficient, this clause is a small wrapper of the condition group builder
        $where = $this->createWhere();
        $where->where('amount', '>', 1000)
            ->orWhere('amount', '<', 1000)
            ->where('description', 'is null');

        $this->assertCompiles('WHERE (amount > 1000) OR (amount < 1000) AND (description IS NULL)', $where);
    }
}
