# UPDATE Query

## Sample

```php
$query = $connection
    ->update()
    ->table('accounts')
    ->values(['name' => 'Account 3'])
    ->where('name', '=', 'Account 1');

$statement = $connection->query($query);
```

You can get the number of affected rows:

```php
$rowCount = $statement->getRowCount();
```

## Table

The table can be given an alias:

```php
$query->table('accounts', 'a');
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
use Foundry\Parameter;

$query = $connection
    ->update()
    ->table('accounts')
    ->values(['name' => new Parameter])
    ->where('name', '=', new Parameter);

$statement = $connection->query($query, ['Account 3', 'Account 1']);
```

## Named Parameters

Usage:

```php
use Foundry\Parameter;

$query = $connection
    ->update()
    ->table('accounts')
    ->values(['name' => new Parameter('new')])
    ->where('name', '=', new Parameter('old'));

$statement = $connection->query($query, [':new' => 'Account 3', ':old' => 'Account 1']);
```

## Reset

All clauses are additive.
For example, adding a join to the query will not remove previously declared joins.

To reset a part of the query, you can use the `reset` method:

```php
public function reset($part = null);
```

Usage:

```php
use Foundry\Builder\Statement\Update;

$query->reset(Update::PART_JOIN);
```

To completely reset the query:

```php
$query->reset();
```

The `reset` method is also provided by the other statements (select, insert, delete).
