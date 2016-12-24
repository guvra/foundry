<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Sqlite\Data;

use Guvra\Builder\Data\Insert as BaseInsert;

/**
 * Insert builder for Sqlite.
 */
class Insert extends BaseInsert
{
    /**
     * {@inheritdoc}
     */
    protected function buildIgnore($value)
    {
        return $value ? ' OR IGNORE' : '';
    }
}
