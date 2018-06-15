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
 * Test the WHERE builder.
 */
class WhereTest extends AbstractTestCase
{
    public function testWhere()
    {
        // A single test is sufficient, this clause is a small wrapper of the condition group builder
        $where = $this->createWhere();
        $where->where('amount', '>', 1000)
            ->orWhere('amount', '<', 1000)
            ->where('description', 'is null');

        $this->assertEquals('WHERE (amount > 1000) OR (amount < 1000) AND (description IS NULL)', $where->toString());
    }
}
