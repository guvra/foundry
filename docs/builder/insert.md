# INSERT Query

## Sample

```php
$query = $connection
    ->insert()
    ->ignore()
    ->into('accounts')
    ->columns(['name', 'balance'])
    ->values(['Account 1', 0]);

$statement = $connection->query($query);
```

You can get the number of affected rows:

```php
$rowCount = $statement->getRowCount();
```

## Multiple Inserts

This query builder can handle the creation of multiple rows in a single INSERT query.

```php
$query = $connection
    ->insert()
    ->into('accounts')
    ->columns(['name', 'balance'])
    ->values([['Account 2', 50], ['Account 3', 0], ['Account 4', 0]]);

$statement = $connection->query($query);
```

## Reset

Adding values to the query will not remove previously declared values.

To reset a part of the query, you can use the `reset` method:

```php
public function reset($part = null);
```

Usage:

```php
use Foundry\Builder\Statement\Insert;

$query->reset(Insert::PART_VALUES);
```

To completely reset the query:

```php
$query->reset();
```

The `reset` method is also provided by the other statements (select, update, delete).
