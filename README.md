# PHP Query Builder

## Description

A simple yet powerful SQL query builder written in PHP.

This is a work in progress.

## Usage

Instanciation:

```php
use Guvra\Connection;

$connection = new Connection(['dsn' => 'sqlite:db.sqlite']);
```

Select:

```php
$statement = $connection
    ->select()
    ->from('transactions')
    ->join('accounts', 'accounts.account_id,  '=', 'transaction.account_id')
    ->where('accounts.name', 'like', '%account%')
    ->orWhere('accounts.balance', 'between', [0, 1000])
    ->limit(3, 1)
    ->order('record_id', 'desc')
    ->query();

$rows = $statement->fetchAll();
```

Complex conditions:

```php
$select->where(function ($condition) {
    $condition->where('amount < 0 OR amount > 100')
        ->orWhere('amount', '=', 40);
})
```

Insert:

```php
$statement = $this->query
    ->into('accounts')
    ->columns(['name', 'balance'])
    ->values(['Account 4', 500])
    ->query();

$rowCount = $statement->getRowCount();
```

Update:

```php
$statement = $this->connection
    ->update()
    ->table('accounts')
    ->values(['name' => 'Account 5'])
    ->where('name', '=', 'Account 1')
    ->query();

$rowCount = $statement->getRowCount();
```

Delete:

```php
$statement = $this->connection
    ->delete()
    ->from('accounts')
    ->where('name', '=', 'Account 1')
    ->query();

$rowCount = $statement->getRowCount();
```
