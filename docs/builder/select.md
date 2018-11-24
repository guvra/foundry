# SELECT Query

## Example

```php
use Foundry\Parameter;

$select = $connection
    ->select()
    ->from('transactions')
    ->join('accounts', 'accounts.account_id transactions.account_id')
    ->where('accounts.name', 'like', new Parameter('name'))
    ->orWhere('accounts.balance', 'between', [0, 1000])
    ->order('transactions.date desc');

$statement = $connection->query($select, [':name' => '%stock%']);
$rows = $statement->fetchAll();
```

## DISTINCT

The `DISTINCT` modifier can be set with the `distinct` method.

```php
public function distinct(bool $value = true);
```

Usage:

```php
$query->distinct();
```

You can revert it:

```php
$query->distinct(false);
```

## Fields

The select fields can be defined with the `columns` method.

```php
public function columns(array $columns);
```

Any column can be given an alias by specifying a string value as the array key.

Usage:

```php
$query->columns(['name', 'min_amount' => 'min(amount)']);
```

You can use a sub query:

```php
$query->columns(['count' => $subQuery]); // $subQuery is a select object
```

You can also build the sub query with a callback:

```php
$query->columns(['count' => function (Select $subQuery) {
    // Build the sub query here
}]);
```

## FROM

The FROM clause can be defined with the `from` method.

```php
public function from($tables);
```

The `$tables` parameter can be a string or an array.
Any table can be given an alias by specifying a string value as the array key.

Usage:

```php
$query->from('transactions');
```

```php
$query->from(['t' => 'transactions', 'a' => 'accounts']);
```

## JOIN

This clause is shared by multiple queries (SELECT, UPDATE, DELETE).

The following [section of the documentation](join.md) explains how to use the JOIN clause.

## WHERE

This clause is shared by multiple queries (SELECT, UPDATE, DELETE).

The following [section of the documentation](conditions.md) explains how to use the WHERE clause.

## GROUP BY

The GROUP BY clause can be defined with the `group` method.

```php
public function group($columns);
```

The `$columns` parameter can be a string or an array.

Usage:

```php
$query->group('account_id');
```

```php
$query->group(['name', 'amount']);
```

## HAVING

The HAVING clause is handled exactly the same way as the WHERE clause.

The methods are named after the same logic (e.g. `havingIn` instead of `whereIn`).

## ORDER

The ORDER BY clause can be defined with the `order` method.

```php
public function order($orders);
```

Usage:

```php
$query->order('position desc');
```

To set multiple orders:

```php
$query->order(['position desc', 'id desc']);
```

## LIMIT

The LIMIT clause can be defined with the `limit` method.

```php
public function limit(int $max, int $start = 0);
```

Usage:

```php
$query->limit(10);
```

## UNION

The UNION clause can be defined with the `union` method.

```php
public function union($query, $all = false);
```

Set `$all` to true to use UNION ALL instead of UNION.

Usage:

```php
$query->union($subQuery1);
$query->union($subQuery2, true);
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
use Foundry\Builder\Statement\Select;

$query->reset(Select::PART_JOIN);
```

To completely reset the query:

```php
$query->reset();
```

The `reset` method is also provided by the other statements (insert, update, delete).
