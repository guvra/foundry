<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
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
    protected function buildIgnore()
    {
        return $this->ignore ? ' OR IGNORE' : '';
    }
}
