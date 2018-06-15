<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Sqlite\Clause\Insert;

use Guvra\Builder\Clause\Insert\Ignore as BaseIgnore;

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
