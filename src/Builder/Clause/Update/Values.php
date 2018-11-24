<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Clause\Update;

use Foundry\Builder\Builder;

/**
 * Values builder.
 */
class Values extends Builder
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * Set the values.
     *
     * @param array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = $values;
        $this->compiled = null;

        return $this;
    }

    /**
     * Add values.
     *
     * @param array $values
     * @return $this
     */
    public function addValues(array $values)
    {
        $this->values = array_merge($this->values, $values);
        $this->compiled = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function compile()
    {
        if (empty($this->values)) {
            return '';
        }

        $parts = [];

        foreach ($this->values as $column => $value) {
            if (is_string($value)) {
                $value = $this->connection->quote($value);
            } else {
                $value = $this->parseSubQuery($value);
            }

            $parts[$column] = "$column = $value";
        }

        return 'SET ' . implode(', ', $parts);
    }

    /**
     * {@inheritdoc}
     */
    protected function decompile()
    {
        unset($this->values);
        $this->values = [];

        return $this;
    }
}
