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
use Guvra\Builder\Parameter;

$select = $connection
    ->select()
    ->from('transactions')
    ->join('accounts', 'accounts.account_id',  '=', 'transaction.account_id')
    ->where('accounts.name', 'like', new Parameter('name'))
    ->orWhere('accounts.balance', 'between', [0, 1000])
    ->limit(3, 1)
    ->order('record_id', 'desc');

$statement = $connection->prepare($select, [':name' => '%account%']);
$rows = $statement->fetchAll();
```

Sub queries:

```php
$subQuery = $connection->select()
    ->from('transactions')
    ->where('transactions.amount', '>', 50);

$select->where('transactions.transaction_id', 'in', $subQuery)
```

Complex conditions:

```php
$select->where(function ($condition) {
    $condition->where('amount < 0 OR amount > 100')
        ->orWhere('amount', '=', 40);
})
```

Complex joins:

```php
use Guvra\Builder\Expression;

$select->join('accounts', function ($condition) {
    $condition->where('accounts', 'accounts.account_id',  '=', new Expression('transaction.account_id'))
        ->orWhere('store_id', '=', 0);
})
```

Insert:

```php
$query = $this->query
    ->ignore()
    ->into('accounts')
    ->columns(['name', 'balance'])
    ->values(['Account 4', 500]);

$statement = $connection->prepare($select);
$rowCount = $statement->getRowCount();
```

Update:

```php
$query = $this->connection
    ->update()
    ->table('accounts')
    ->values(['name' => 'Account 5'])
    ->where('name', '=', 'Account 1');

$statement = $connection->prepare($select);
$rowCount = $statement->getRowCount();
```

Delete:

```php
$query = $this->connection
    ->delete()
    ->from('accounts')
    ->where('name', '=', 'Account 1');

$statement = $connection->prepare($select);
$rowCount = $statement->getRowCount();
```
