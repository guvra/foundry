<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder\Clause;

use Guvra\Builder\Clause\ConditionGroup;
use Tests\AbstractTestCase;

/**
 * Test the condition group builder.
 */
class ConditionGroupTest extends AbstractTestCase
{
    public function testBasicUsage()
    {
        $conditionGroup = $this->createConditionGroup();
        $conditionGroup->where('amount', '<', 0)
            ->orWhere('amount', '>', 1000);

        $this->assertEquals('(amount < 0) OR (amount > 1000)', $conditionGroup);
    }

    public function testAddCondition()
    {
        $conditionGroup = $this->createConditionGroup();
        $conditionGroup->addCondition($this->createCondition('amount', '<', 0));
        $conditionGroup->addOrCondition($this->createCondition('amount', '>', 1000));

        $this->assertEquals('(amount < 0) OR (amount > 1000)', $conditionGroup);
    }

    public function testWithEncloseDisabled()
    {
        $conditionGroup = $this->createConditionGroup();
        $conditionGroup->setEnclose(false);
        $conditionGroup->where('amount', '<', 0)
            ->orWhere('amount', '>', 1000);

        $this->assertEquals('amount < 0 OR amount > 1000', $conditionGroup);
    }

    public function testEmptyConditionGroup()
    {
        $conditionGroup = $this->createConditionGroup();
        $this->assertEquals('', $conditionGroup);
    }

    public function testWithEmptyCondition()
    {
        $conditionGroup = $this->createConditionGroup();
        $conditionGroup->where(function (ConditionGroup $conditionGroup) {});
        $this->assertEquals('', $conditionGroup);
    }
}
