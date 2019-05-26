<?php
/**
 * Attogram Database
 * @see https://github.com/attogram/database
 */
declare(strict_types = 1);

namespace Attogram\Database;

use Exception;
use PDO;

use function file_exists;
use function in_array;
use function is_writable;
use function print_r;
use function touch;

class Database
{
    /** @var string */
    const VERSION = '1.1.1';

    /** @var bool */
    private $connected = false;

    /** @var array */
    private $createTables = [];

    /** @var string */
    private $databaseFile = 'database.sqlite';

    /** @var PDO */
    private $pdo;

    /**
     * @param string $databaseFile
     */
    public function setDatabaseFile(string $databaseFile)
    {
        $this->databaseFile = $databaseFile;
    }

    /**
     * @param string|array $createTables
     */
    public function setCreateTables($createTables)
    {
        if (empty($createTables)) {
            return;
        }
        if (is_string($createTables)) {
            $this->createTables[] = $createTables;
            return;
        }
        if (is_array($createTables)) {
            $this->createTables = array_merge($this->createTables, $createTables);
        }
    }

    /**
     * @throws Exception
     */
    public function connect()
    {
        if (empty($this->databaseFile)) {
            throw new Exception('Database File not set');
        }
        if (!in_array('sqlite', PDO::getAvailableDrivers())) {
            throw new Exception('sqlite driver not found');
        }
        $doCreateTables = false;
        if (!file_exists($this->databaseFile)) {
            touch($this->databaseFile);
            $doCreateTables = true;
        }
        if (!is_readable($this->databaseFile)) {
            throw new Exception('Database is not readable');
        }
        if (!is_writable($this->databaseFile)) {
            throw new Exception('Database is not writable');
        }
        $this->pdo = new PDO('sqlite:'. $this->databaseFile);
        $this->connected = true;
        if ($doCreateTables && !empty($this->createTables)) {
            $this->createTables();
        }
    }

    /**
     * @throws Exception
     */
    private function createTables()
    {
        foreach ($this->createTables as $table) {
            $this->raw($table);
        }
    }

    /**
     * SQL query, return results as an array
     *
     * @param string $sql
     * @param array $bind
     * @return array
     * @throws Exception
     */
    public function query(string $sql, array $bind = []): array
    {
        if (!$this->connected) {
            $this->connect();
        }
        $statement = $this->pdo->prepare($sql);
        if (!$statement) {
            $this->pdoFail('query: prepare statement failed');
        }
        if (!$statement->execute($bind)) {
            $this->pdoFail('query: statement execute failed');
        }
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$result && ($this->pdo->errorCode() != '00000')) {
            $this->pdoFail('query: statement fetchAll failed');
        }

        return $result;
    }

    /**
     * Raw SQL query
     *
     * @param string $sql
     * @param array $bind
     * @return bool
     * @throws Exception
     */
    public function raw(string $sql, array $bind = []): bool
    {
        if (!$this->connected) {
            $this->connect();
        }
        $statement = $this->pdo->prepare($sql);
        if (!$statement) {
            $this->pdoFail('raw: prepare statement failed');
        }
        $result = $statement->execute($bind);
        if (!$result && ($this->pdo->errorCode() != '00000')) {
            $this->pdoFail('raw: execute statement failed');

            return false;
        }

        return true;
    }

    /**
     * @param string $message
     * @throws Exception
     */
    private function pdoFail(string $message = 'ERROR')
    {
        throw new Exception(
            $message . ' : ' . $this->pdo->errorCode()
            . ' : ' . print_r($this->pdo->errorInfo(), true)
        );
    }
}
