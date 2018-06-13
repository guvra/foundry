# WHERE clause

## Basic Usage

Conditions are used in SELECT, UPDATE and DELETE queries.

There are two methods available:

```php
public function where($column, $operator = null, $value = null);
public function orWhere($column, $operator = null, $value = null);
```

To build a condition that uses a comparison operator:

```php
$query->where('name', '=', 'John');
```

This example compiles into:

```sql
WHERE (name = "John")
```

You can use any valid SQL operator. A few examples:  
`=`, `<>`, `>`, `>=`, `<`, `<=`, `like`, `not like`, `regexp`, `not regexp`

String values are always escaped.
To prevent a value from being escaped, you can use an Expression object:

```php
use Guvra\Builder\Expression;

$query->where('name', '=', new Expression('nickname'));
```

This compiles into:

```sql
WHERE (name = nickname)
```

## NULL / NOT NULL

Usage:

```php
$query->where('description', 'is null');
```

To negate the condition, use `is not null`.

## BETWEEN / NOT BETWEEN

Usage:

```php
$query->where('amount', 'between', [0, 1000]);
```

To negate the condition, use `not between`.

## EXISTS / NOT EXISTS

Usage:

```php
$query->where($subQuery, 'exists');
```

To negate the condition, use `not exists`.

## IN / NOT IN

Usage:

```php
$query->where('name', 'in', ['John', 'Jane']);
```

You can also use a sub query:

```php
$query->where('name', 'in', $subQuery);
```

To negate the condition, use `not in`.

## FIND_IN_SET

This is specific to MySQL.

Usage:

```php
$query->where('name', 'in set', ['John', 'Jane']);
```

This compiles into:

```sql
WHERE FIND_IN_SET(name, "John,Jane");
```

You can also specify a null value:

```php
$query->where('name', 'in set', null);
```

To negate the condition, use `not in set`.

## Plain Condition

To build a plain condition, use only the 1st function parameter:

```php
$query->where('amount = 1000');
```

## Unnamed Parameters

Usage:

```php
use Guvra\Builder\Parameter;

$query = $connection
    ->select()
    ->from('accounts')
    ->where('name', '=', new Parameter);

$statement = $connection->query($query, [$name]);
$rows = $statement->fetchAll();
```

This compiles into:

```sql
SELECT * FROM accounts WHERE (name = ?)
```

Using an expression also works: `new Expression('?')`).
It is not recommended though, the purpose of expressions is to disable string escaping.

## Named Parameters

Usage:

```php
use Guvra\Builder\Parameter;

$query = $connection
    ->select()
    ->from('accounts')
    ->where('name', '=', new Parameter('name'));

$statement = $connection->query($query, [':name' => $name]);
$rows = $statement->fetchAll();
```

This compiles into:

```sql
SELECT * FROM accounts WHERE (name = :name)
```

Using an expression also works: `new Expression(':name')`.
It is not recommended though, the purpose of expressions is to disable string escaping.

## Condition Group

To build complex combinations of AND/OR conditions, you can use condition groups.

```php
use Guvra\Builder\Clause\ConditionGroup;

$query->where('description', 'like', '%computer%');
$query->where(function (ConditionGroup $condition) {
    $condition->where('amount', '<', 1000)
        ->orWhere('amount', >, 1000)
});
```

This compiles into:

```sql
WHERE (description like "%computer%") AND (amount < 1000 OR amount > 1000)
```

## Sub Query

Usage:

```php
$query->where('id', '=', $subQuery);
```

It works both ways:

```php
$query->where($subQuery, '=', 2);
```

This may not always yield the same results, since the third parameter is escaped when it is a string.

It also works with callbacks:

```php
use Guvra\Builder\Statement\Select;

$query->where('id', '=', function (Select $subQuery) {
    $subQuery->from('user')->columns('user_id')->where('username', '=', 'admin');
});
```

## Shortcut Methods

Shortcut methods can be used for convenience. For example:

```php
$query->whereBetween('amount', [0, 1000]);
$query->whereIn('name', ['John', 'Jane']);
$query->orWhereNotIn('id', $subQuery);
$query->whereExists($subQuery);
$query->orWhereNotNull('description');
```
