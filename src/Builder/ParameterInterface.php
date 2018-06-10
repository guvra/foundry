<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

/**
 * Parameter interface.
 */
interface ParameterInterface
{
    /**
     * Get the name of the parameter.
     *
     * @return string
     */
    public function getName();
}
