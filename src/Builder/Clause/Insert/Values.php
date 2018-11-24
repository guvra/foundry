<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Builder\Clause\Insert;

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
     * Set the values to insert.
     *
     * @param array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        if (empty($values)) {
            return $this;
        }

        if (!is_array(current($values))) {
            $this->values = [$values];
        } else {
            $this->values = $values;
        }

        $this->compiled = null;

        return $this;
    }

    /**
     * Add values to insert.
     *
     * @param array $values
     * @return $this
     */
    public function addValues(array $values)
    {
        if (empty($values)) {
            return $this;
        }

        if (!is_array(current($values))) {
            $this->values[] = $values;
        } else {
            $this->values = array_merge($this->values, $values);
        }

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

        foreach ($this->values as $value) {
            $parts[] = $this->prepareValues($value);
        }

        return 'VALUES ' . implode(',', $parts);
    }

    /**
     * Convert the values to an inline string.
     *
     * @param array $values
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function prepareValues(array $values)
    {
        foreach ($values as $key => $value) {
            if (is_string($value)) {
                $values[$key] = $this->connection->quote($value);
            }
        }

        return '(' . implode(',', $values) . ')';
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
