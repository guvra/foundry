<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Guvra;

use Guvra\Builder\BuilderFactory;
use Guvra\Builder\BuilderInterface;
use Guvra\Builder\ExpressionInterface;

/**
 * Connection class.
 */
class Connection implements ConnectionInterface
{
    /**
     * @var BuilderFactory
     */
    protected $builderFactory;

    /**
     * @var StatementFactory
     */
    protected $statementFactory;

    /**
     * @var \Pdo
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @param array $options
     * @throws \PDOException
     */
    public function __construct(array $options)
    {
        $options += [
            'dsn' => 'false',
            'username' => 'root',
            'password' => '',
            'driver_options' => null,
            'error_mode' => \PDO::ERRMODE_EXCEPTION,
            'statement_factory' => null,
            'builder_factory' => null,
        ];

        // Set the database driver
        if (!preg_match('/(\w+):/', $options['dsn'], $matches)) {
            throw new \PDOException('Invalid database driver.');
        }
        $this->driver = $matches[1];

        // Create the PDO connection
        $this->pdo = new \PDO($options['dsn'], $options['username'], $options['password'], $options['driver_options']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, $options['error_mode']);

        // Initialize factories
        $this->statementFactory = $options['statement_factory'] ?: new StatementFactory;
        $this->builderFactory = $options['builder_factory'] ?: new BuilderFactory($this);
    }

    /**
     * {@inheritdoc}
     */
    public function query($query, array $bind = [])
    {
        if (is_object($query) && $query instanceof BuilderInterface) {
            $query = $query->build();
        }

        if (empty($bind)) {
            $pdoStatement = $this->pdo->query($query);
        } else {
            $pdoStatement = $this->pdo->prepare($query);
            $pdoStatement->execute($bind);
        }

        return $this->statementFactory->create($pdoStatement);
    }

    /**
     * {@inheritdoc}
     */
    public function exec($query)
    {
        if (is_object($query) && $query instanceof BuilderInterface) {
            $query = $query->__toString();
        }

        return $this->pdo->exec($query);
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($query)
    {
        if (is_object($query) && $query instanceof BuilderInterface) {
            $query = $query->__toString();
        }

        $pdoStatement = $this->pdo->prepare($query);
        return $this->statementFactory->create($pdoStatement);
    }

    /**
     * {@inheritdoc}
     */
    public function quote($value)
    {
        if (is_object($value) && $value instanceof ExpressionInterface) {
            // Never quote expressions
            return $value;
        }

        return $this->pdo->quote($value);
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        return $this->pdo->rollback();
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * Create a select query.
     *
     * @return \Guvra\Builder\Data\Select
     */
    public function select()
    {
        return $this->builderFactory->create('select', $this->builderFactory);
    }

    /**
     * Create an insert query.
     *
     * @return \Guvra\Builder\Data\Insert
     */
    public function insert()
    {
        return $this->builderFactory->create('insert', $this->builderFactory);
    }

    /**
     * Create an update query.
     *
     * @return \Guvra\Builder\Data\Update
     */
    public function update()
    {
        return $this->builderFactory->create('update', $this->builderFactory);
    }

    /**
     * Create a delete query.
     *
     * @return \Guvra\Builder\Data\Delete
     */
    public function delete()
    {
        return $this->builderFactory->create('delete', $this->builderFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getRowCount($tableName, $callback = null)
    {
        $query = $this->select()
            ->from($tableName)
            ->columns(['COUNT(*)']);

        if ($callback) {
            call_user_func($callback, $query);
        }

        return $this->query($query)->fetchOne();
    }

    /**
     * {@inheritdoc}
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Get the PDO connection used internally.
     *
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}