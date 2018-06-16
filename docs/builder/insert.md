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
