<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra;

/**
 * Table class.
 */
class Table implements TableInterface
{
    const KEY_UNIQUE = 1;
    const KEY_INDEX = 2;
    const KEY_FULLTEXT = 3;

    const ACTION_CASCADE = 1;
    const ACTION_SET_NULL = 2;
    const ACTION_NONE = 3;

    const TYPE_INTEGER = 1;
    const TYPE_DECIMAL = 2;
    const TYPE_VARCHAR = 3;
    const TYPE_TEXT = 4;
    const TYPE_DATETIME = 5;
    const TYPE_TIMESTAMP = 6;
    const TYPE_BLOB = 7;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $keys;

    /**
     * @var array
     */
    protected $foreignKeys;

    /**
     * @var array
     */
    protected $options;

    /**
     * {@inheritdoc}
     */
    public function setName($value)
    {
        $this->name = (string) $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addColumn($name, array $definition)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addForeignKey($name, $sourceColumn, $targetTable, $targetColumn)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addKey($name, $columns, $type)
    {
        return $this;
    }
}
