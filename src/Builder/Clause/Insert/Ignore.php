<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause\Insert;

use Guvra\Builder\Builder;

/**
 * IGNORE builder.
 */
class Ignore extends Builder
{
    /**
     * @var bool
     */
    protected $value = false;

    /**
     * Set the IGNORE value.
     *
     * @param bool $value
     * @return $this
     */
    public function setValue(bool $value)
    {
        $this->value = $value;
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        return $this->value ? 'IGNORE' : '';
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        $this->value = false;

        return $this;
    }
}
