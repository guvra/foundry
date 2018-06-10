<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
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
