# Attogram Database

ALPHA RELEASE

PHP access to SQLite databases

[![Maintainability](https://api.codeclimate.com/v1/badges/473e68db98ac442429c1/maintainability)](https://codeclimate.com/github/attogram/database/maintainability)
[![Build Status](https://travis-ci.org/attogram/database.svg?branch=master)](https://travis-ci.org/attogram/database)

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
