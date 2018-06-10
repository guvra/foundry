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
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
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
        return $this->getValue();
    }
}
