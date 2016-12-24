<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Data;

use Guvra\Builder\BuilderFactoryInterface;
use Guvra\Builder\Condition\ConditionGroup;
use Guvra\Builder\QueryableBuilder;
use Guvra\ConnectionInterface;

/**
 * Delete builder.
 */
class Delete extends QueryableBuilder
{
    use \Guvra\Builder\Traits\WhereTrait;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        return 'DELETE'
               . $this->buildTable($this->table, 'FROM')
               . $this->buildWhere();
    }

    /**
     * Set the FROM clause.
     *
     * @param string $table
     * @return $this
     */
    public function from($table)
    {
        $this->table = (string) $table;

        return $this;
    }
}
