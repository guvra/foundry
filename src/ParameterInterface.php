<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra;

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
