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
$query->join('accounts', 'accounts.account_id = transactions.account_id');
```

An alias can be defined by using an array:

```php
$query->join(['a' => 'accounts', 'a.account_id = t.account_id');
```

## Advanced Conditions

The 2nd, 3rd et 4th parameters can be used to build a condition, with the same rules as the WHERE clause:

```php
use Foundry\Parameter;

$query->join(['a' => 'accounts', 'a.account_id', '=', new Parameter);
```

## Multiple Conditions

To build a complex join condition, use a callback function:

```php
use Foundry\Builder\Clause\ConditionGroup;

$query->join('accounts', function (ConditionGroup $group) {
    $group->where('accounts.account_id = transactions.account_id');
    $group->where('accounts.description', 'not null');
});
```
