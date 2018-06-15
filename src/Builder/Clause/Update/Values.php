<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause\Update;

use Guvra\Builder\Builder;

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
     * Set the table.
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
