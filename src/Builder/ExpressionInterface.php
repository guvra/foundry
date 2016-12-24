<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

/**
 * Expression interface.
 */
interface ExpressionInterface
{
    /**
     * Get the value of the expression.
     *
     * @return string
     */
    public function getValue();
}
