<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\ConditionGroup;

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
