# Querying

## Execute Queries

Execute a SQL statement and get a statement object:

```php
$statement = $connection->query($query);
$rows = $statement->fetchAll();
```

Execute a SQL statement and get the number of affected rows:

```php
$rowCount = $connection->exec($query);
```

Prepare a statement before execution:

```php
$statement = $connection->prepare($query);
$statement->execute();
$rows = $statement->fetchAll();
```

## Bind Values

Using the `query` method:

```php
$statement = $connection->query($query, $bind);
```

Using the `prepare` method:

```php
$statement = $connection->prepare($query);
$statement->execute($bind);
```

Example:

```php
use Guvra\Builder\Parameter;

$query = $connection
    ->select()
    ->from('accounts')
    ->where('name', '=', new Parameter('name'));

$statement = $connection->query($query, [':name' => $name]);
$rows = $statement->fetchAll();
```
