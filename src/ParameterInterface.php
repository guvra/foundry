<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry;

/**
 * Parameter interface.
 */
interface ParameterInterface
{
    /**
     * Get the parameter value.
     *
     * @return string
     */
    public function toString();
}
