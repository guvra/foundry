<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra\Builder\Clause;

use Guvra\Builder\AbstractBuilder;
use Guvra\ConnectionInterface;

/**
 * Union clause.
 */
class Union extends AbstractBuilder
{
    /**
     * @var array
     */
    protected $queries;

    /**
     * @var string
     */
    protected $haystack;

    /**
     * @param ConnectionInterface $connection
     * @param array $queries
     */
    public function __construct(ConnectionInterface $connection, array $queries)
    {
        parent::__construct($connection);
        $this->queries = $queries;
    }

    /**
     * Add a query to the UNION clause.
     *
     * @param BuilderInterface|string $query
     * @return $this
     */
    public function addQuery($query)
    {
        $this->queries[] = $query;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $result = '';

        foreach ($this->queries as $query) {
            if ($query instanceof BuilderInterface) {
                // Build the query manually, because __toString method must not throw exceptions
                $query = $query->build();
            }

            $result .= ' UNION ' . $query;
        }

        return $result;
    }
}
