<?php
/**
 * Attogram Database
 * @see https://github.com/attogram/database
 *
 * Example: two tables
 */
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

