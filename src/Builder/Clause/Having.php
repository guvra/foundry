<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

/**
 * Where clause.
 */
class Having extends Where
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $result = parent::build();

        return $result !== '' ? "HAVING $result" : '';
    }
}
