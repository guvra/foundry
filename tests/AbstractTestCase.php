<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests;

use Guvra\Builder\Clause\Condition;
use Guvra\Builder\Clause\ConditionGroup;
use Guvra\Builder\Clause\Join;
use Guvra\Builder\Clause\JoinGroup;
use Guvra\Builder\Statement\Delete;
use Guvra\Builder\Statement\Insert;
use Guvra\Builder\Statement\Select;
use Guvra\Connection;
use Guvra\Builder\BuilderFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test Connection/Bag/Builder classes.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->connection = new Connection(['dsn' => 'sqlite::memory:']);
    }

    /**
     * Execute the specified callback after generation of test tables.
     *
     * @param callable $callback
     */
    protected function withTestTables($callback)
    {
        // Make sure the table do not already exist
        $this->connection->query('DROP TABLE IF EXISTS `accounts`');

        // Create the "accounts" table
        $query = 'CREATE TABLE `accounts`(
                      `account_id` integer primary key not null,
                      `name` text,
                      `balance` real not null,
                      constraint name_unique unique(`name`)
                  )';
        $this->connection->query($query);

        // Insert test data into the tables
        $query = 'INSERT INTO `accounts`(`account_id`, `name`, `balance`) VALUES
                  (1, "Account 1", "0"),
                  (2, "Account 2", "100")';
        $this->connection->query($query);

        // Execute the callback
        call_user_func($callback, $this->connection);

        // Drop the tables
        $this->connection->query('DROP TABLE `accounts`');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->connection = null;
    }

    /**
     * @return Select
     */
    protected function createSelect()
    {
        return $this->connection
            ->select()
            ->from('accounts');
    }

    /**
     * @return Insert
     */
    protected function createInsert()
    {
        return $this->connection->insert();
    }

    /**
     * @return Delete
     */
    protected function createDelete()
    {
        return $this->connection->delete();
    }

    /**
     * @param mixed ...$args
     * @return Condition
     */
    protected function createCondition(...$args)
    {
        return $this->connection->getBuilderFactory()->create('condition', ...$args);
    }

    /**
     * @return ConditionGroup
     */
    protected function createConditionGroup()
    {
        return $this->connection->getBuilderFactory()->create('conditionGroup');
    }

    /**
     * @param string $type
     * @param string|array $table
     * @param Condition $condition
     * @return Join
     */
    protected function createJoin(string $type, $table, Condition $condition = null)
    {
        return $this->connection->getBuilderFactory()->create('join', $type, $table, $condition);
    }

    /**
     * @return JoinGroup
     */
    protected function createJoinGroup()
    {
        return $this->connection->getBuilderFactory()->create('joinGroup');
    }
}
