# Attogram Database

_ALPHA RELEASE_

PHP access to SQLite databases

## Install

```
composer require attogram/database
```

## Example

```php
<?php
declare(strict_types = 1);

use Attogram\Database\Database;

require '../vendor/autoload.php';

$database = new Database();

$database->setDatabaseFile('./test.sqlite');

$database->setCreateTables("CREATE TABLE 'foo' ('bar' TEXT)");

$database->raw("INSERT INTO foo ('bar') VALUES ('baz')");

$arrayResults = $database->query("SELECT * FROM 'foo'");

print_r($arrayResults);
```
