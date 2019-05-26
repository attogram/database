# Attogram Database

SQLite database access for PHP 7.

[![Maintainability](https://api.codeclimate.com/v1/badges/473e68db98ac442429c1/maintainability)](https://codeclimate.com/github/attogram/database/maintainability)
[![Build Status](https://travis-ci.org/attogram/database.svg?branch=master)](https://travis-ci.org/attogram/database)

## Install

```
composer require attogram/database
```

## Examples

one table:

```php
declare(strict_types = 1);

use Attogram\Database\Database;

require '../vendor/autoload.php';

$database = new Database();
$database->setDatabaseFile('./test.one.sqlite');
$database->setCreateTables("CREATE TABLE 'one' ('foo' TEXT)");

try {
    $database->raw("INSERT INTO one ('foo') VALUES (CURRENT_TIMESTAMP)");
    $arrayResults = $database->query("SELECT * FROM 'one'");
    print_r($arrayResults);
} catch (Throwable $error) {
    print 'ERROR: ' . $error->getMessage();
}
```

two tables:

```php
declare(strict_types = 1);

use Attogram\Database\Database;

require '../vendor/autoload.php';

$database = new Database();
$database->setDatabaseFile('./test.two.sqlite');

$tables = [
    "CREATE TABLE 'one' ('foo' TEXT)",
    "CREATE TABLE 'two' ('bar' TEXT)",
];
$database->setCreateTables($tables);

try {
    $database->raw("INSERT INTO one ('foo') VALUES (CURRENT_TIMESTAMP)");
    $database->raw("INSERT INTO two ('bar') VALUES (CURRENT_TIMESTAMP)");
    $arrayResults = $database->query("SELECT * FROM 'one'");
    print_r($arrayResults);
    $arrayResults = $database->query("SELECT * FROM 'two'");
    print_r($arrayResults);
} catch (Throwable $error) {
    print 'ERROR: ' . $error->getMessage();
}
```
