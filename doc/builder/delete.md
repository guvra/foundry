# DELETE Query

## Sample

```php
$query = $connection
    ->delete()
    ->from('accounts')
    ->where('name', '=', 'Account 1');

$statement = $connection->query($query);
```

You can get the number of affected rows:

```php
$rowCount = $statement->getRowCount();
```

## JOIN

This clause is shared by multiple queries (SELECT, UPDATE, DELETE).

The following [section of the documentation](join.md) explains how to use the JOIN clause.

## WHERE

This clause is shared by multiple queries (SELECT, UPDATE, DELETE).

The following [section of the documentation](conditions.md) explains how to use the WHERE clause.

## Unnamed Parameters

Usage:

```php
use Guvra\Parameter;

$query = $connection
    ->delete()
    ->from('accounts')
    ->where('name', '=', new Parameter);

$statement = $connection->query($query, ['Account 1']);
```

## Named Parameters

Usage:

```php
use Guvra\Parameter;

$query = $connection
    ->delete()
    ->from('accounts')
    ->where('name', '=', new Parameter('name'));

$statement = $connection->query($query, [':name' => 'Account 1']);
```
