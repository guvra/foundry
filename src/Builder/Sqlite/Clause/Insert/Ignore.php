<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Sqlite\Clause\Insert;

use Foundry\Builder\Clause\Insert\Ignore as BaseIgnore;

/**
 * IGNORE builder for SQLite.
 */
class Ignore extends BaseIgnore
{
    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        return $this->value ? 'OR IGNORE' : '';
    }
}
