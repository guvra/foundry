# PHP Query Builder

## Description

A simple yet powerful SQL query builder written in PHP.

## Documentation

- [Create a db connection](doc/connection.md)
- [Build a SELECT query](doc/builder/select.md)
- [Build an INSERT query](doc/builder/insert.md)
- [Build a DELETE query](doc/builder/insert.md)
- [Build an UPDATE query](doc/builder/insert.md)
- [Execute the queries](doc/queries.md)

## Usage Example

Initialization:

```php
use Guvra\Connection;

$connection = new Connection(['dsn' => 'sqlite:db.sqlite']);
```

Select:

```php
use Guvra\Builder\Parameter;

$select = $connection
    ->select()
    ->from('transactions')
    ->join('accounts', 'accounts.account_id',  '=', 'transactions.account_id')
    ->where('accounts.name', 'like', new Parameter('name'))
    ->orWhere('accounts.balance', 'between', [0, 1000])
    ->order('transactions.date', 'desc');

$statement = $connection->query($select, [':name' => '%stock%']);
$rows = $statement->fetchAll();
```

Insert:

```php
$query = $connection
    ->insert()
    ->ignore()
    ->into('accounts')
    ->columns(['name', 'balance'])
    ->values(['Account 1', 0]);

$connection->query($query);
```

Update:

```php
$query = $connection
    ->update()
    ->table('accounts')
    ->values(['name' => 'Account 5'])
    ->where('name', '=', 'Account 1');

$connection->query($query);
```

Delete:

```php
$query = $connection
    ->delete()
    ->from('accounts')
    ->where('name', '=', 'Account 1');

$connection->query($query);
```
