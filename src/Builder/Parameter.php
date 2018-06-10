<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

/**
 * Parameter class.
 * Its value will never be quoted by the query builders.
 */
class Parameter implements ParameterInterface
{
    /**
     * @var string|null
     */
    protected $name = null;

    /**
     * @param string $name
     */
    public function __construct(string $name = '?')
    {
        if (strpos($name, ':') !== 0) {
            $name = ':' . $name;
        }

        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the parameter name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
