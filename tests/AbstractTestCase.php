<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2017 guvra
 * @license   MIT Licence
 */
namespace Tests;

use Guvra\Connection;

/**
 * Test Connection/Bag/Builder classes.
 */
abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
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
        $this->connection = new Connection(['dsn' => 'sqlite:tests.db3']); // TODO memory
    }

    /**
     * Execute the specified callback while a table named "test_table" is generated.
     *
     * @param callable $callback
     */
    protected function withTestTables($callback)
    {
        // Make sure the table do not already exist
        $this->connection->query('DROP TABLE IF EXISTS accounts');
        $this->connection->query('DROP TABLE IF EXISTS transactions');

        // Create the "accounts" table
        $query = 'CREATE TABLE accounts(
                      account_id integer primary key not null,
                      name text,
                      balance real not null,
                      constraint name_unique unique(name)
                  )';
        $this->connection->query($query);

        // Create the "transactions" table
        $query = 'CREATE TABLE transactions(
                      transaction_id integer primary key not null,
                      date datetime not null,
                      description text,
                      amount real not null,
                      account_id integer not null,
                      foreign key (account_id) references accounts(id)
                  )';
        $this->connection->query($query);

        // Insert test data into the tables
        $query = 'INSERT INTO accounts(account_id, name, balance) VALUES
                  (1, "Account 1", "0"),
                  (2, "Account 2", "100")';
        $this->connection->query($query);

        $query = 'INSERT INTO transactions(transaction_id, date, description, amount, account_id) VALUES
                  (1, "2017-01-01", "Transaction 1", "-10", 1),
                  (2, "2017-01-01", "Transaction 2", "50", 2),
                  (3, "2017-01-01", "Transaction 3", "-30", 2),
                  (4, "2017-01-02", "Transaction 4", "50", 1),
                  (5, "2017-01-02", "Transaction 5", "-40", 1),
                  (6, "2017-01-02", "Transaction 6", "60", 2),
                  (7, "2017-01-02", "Transaction 7", "-100", 1),
                  (8, "2017-01-02", "Transaction 8", "-40", 2),
                  (9, "2017-01-02", "Transaction 9", "20", 2),
                  (10, "2017-01-02", "Transaction 10", "40", 1),
                  (11, "2017-01-02", "Transaction 11", "40", 2),
                  (12, "2017-01-02", "Transaction 12", "60", 1)';
        $this->connection->query($query);

        // Execute the callback
        call_user_func($callback, $this->connection);

        // Drop the tables
        $this->connection->query('DROP TABLE accounts');
        $this->connection->query('DROP TABLE transactions');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->connection = null;
    }
}
