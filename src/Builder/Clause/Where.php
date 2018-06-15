<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Clause;

use Foundry\Builder\ConditionGroup;

/**
 * WHERE builder.
 */
class Where extends ConditionGroup
{
    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        $result = parent::compile();

        return $result !== '' ? 'WHERE ' . $result : '';
    }
}
