# Connect to the Database

## Basic Usage

Create a new connection:

```php
use Guvra\Builder\Connection;

$connection = new Connection(['dsn' => 'sqlite::memory:']);
```

The following options are available:

- `dsn`
- `username` (`"root"` by default)
- `password`
- `driver_options`
- `error_mode` => (`\PDO::ERRMODE_EXCEPTION` by default)

DSN to use:

- SQLite (memory): `"sqlite::memory:"`
- SQLite (file): `"sqlite:mydb.db3"`
- MySQL: `"mysql:host=localhost;dbname=mydb"`
- PostgreSQL: `"pgsql:host=localhost;dbname=mydb"`

## Connection Bag

The `ConnectionBag` class allows to name and store multiples connections.

```php
use Guvra\Builder\ConnectionBag;

$connectionBag = new ConnectionBag;

// Add the default connection
$connectionBag->addConnection($defaultConnection);

// Add another connection, named "read"
$connectionBag->addConnection($readConnection, 'read');
```

To retrieve a connection:

```php
// Retrieves the default connection
$connection = $connectionBag->getConnection();

// Retrieves the connection named "read"
$connection = $connectionBag->getConnection('read');
```

To remove a connection:

```php
$connectionBag->removeConnection('read');
```

To check if a connection exists:

```php
$exists = $connectionBag->hasConnection('read');
```
