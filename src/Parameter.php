<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra;

/**
 * Parameter class.
 * Its value will never be quoted by the query builders.
 */
class Parameter implements ParameterInterface
{
    /**
     * @var string|null
     */
    protected $value = null;

    /**
     * @param string $value
     */
    public function __construct(string $value = '?')
    {
        $this->setValue($value);
    }

    /**
     * Set the parameter value.
     *
     * @param string $value
     * @return $this
     * @throws \UnexpectedValueException
     */
    protected function setValue(string $value)
    {
        if ($value === '') {
            throw new \UnexpectedValueException('The parameter value is required.');
        }

        if ($value !== '?' && strpos($value, ':') !== 0) {
            $value = ':' . $value;
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Get the parameter value.
     *
     * @return string
     */
    public function toString()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
