<?php
/**
 * Attogram Database
 * @see https://github.com/attogram/database
 *
 * Example: one table
 */
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
