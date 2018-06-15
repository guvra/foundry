<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder;

use Foundry\Builder\ConditionGroup;
use Foundry\Tests\TestCase;

/**
 * Test the condition group builder.
 */
class ConditionGroupTest extends TestCase
{
    public function testBasicUsage()
    {
        $conditionGroup = $this->createConditionGroup();
        $conditionGroup->where('amount', '<', 0)
            ->orWhere('amount', '>', 1000);

        $this->assertEquals('(amount < 0) OR (amount > 1000)', $conditionGroup->toString());
    }

    public function testWithEncloseDisabled()
    {
        $conditionGroup = $this->createConditionGroup();
        $conditionGroup->setEnclose(false);
        $conditionGroup->where('amount', '<', 0)
            ->orWhere('amount', '>', 1000);

        $this->assertEquals('amount < 0 OR amount > 1000', $conditionGroup->toString());
    }

    public function testEmptyConditionGroup()
    {
        $conditionGroup = $this->createConditionGroup();
        $this->assertEmpty($conditionGroup->toString());
    }

    public function testWithEmptyCondition()
    {
        $conditionGroup = $this->createConditionGroup();
        $conditionGroup->where(function (ConditionGroup $conditionGroup) {});
        $this->assertEmpty($conditionGroup->toString());
    }

    public function testReset()
    {
        $conditionGroup = $this->createConditionGroup();
        $conditionGroup->where('amount', '=', 50);
        $this->assertNotEmpty($conditionGroup->toString());

        $conditionGroup->reset();
        $this->assertEmpty($conditionGroup->toString());
    }
}
