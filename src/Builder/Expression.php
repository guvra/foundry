<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder;

/**
 * Expression class.
 * Its value will never be quoted by the query builders.
 */
class Expression implements ExpressionInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
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
            throw new \UnexpectedValueException('The expression value is required.');
        }

        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return $this->value;
    }

    /**
     * Get the expression value.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
