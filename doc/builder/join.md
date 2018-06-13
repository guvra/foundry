# JOIN clause

## Basic Usage

Joins are used in SELECT, UPDATE and DELETE queries.

There are five methods available:

```php
public function join($table, ...$args);
public function joinLeft($table, ...$args);
public function joinRight($table, ...$args);
public function joinCross($table);
public function joinNatural($table);
```

Usage:

```php
$query->join('accounts', 'accounts.account_id', '=', 'transactions.account_id');
```

## Complex Conditions

To build a complex join condition, use a callback function:

```php
use Guvra\Builder\Clause\ConditionGroup;
use Guvra\Builder\Expression;

$query->join('accounts', function (ConditionGroup $group) {
    $group->where('accounts.account_id', '=', new Expression('transactions.account_id'));
    $group->where('accounts.description', 'is not null');
});
```

The expression object prevents the value from being escaped by the query builder.
Otherwise, the value would have been compiled into  `"transactions.account_id"`.

## Plain Condition

Plain strings also work:

```php
$query->joinLeft('accounts', 'accounts.account_id = transactions.account_id');
```
