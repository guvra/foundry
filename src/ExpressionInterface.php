<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry;

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
    public function toString();
}
