<?php

namespace App\Platform\Database;

use App\Platform\Database\ObjectMapper\MysqliMappingIterator;
use Iterator;
use mysqli;
use RuntimeException;

class MysqliConnection implements DatabaseConnectionInterface
{

    private string  $host;
    private string  $username;
    private string  $password;

    private static mysqli $connection;

    public function __construct(string $host, string $username, string $password, ?string $dbName = null)
    {
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;

        if (!empty(self::$connection)) {
            self::$connection->close();
        }

        self::$connection = new mysqli($host, $username, $password, $dbName);

        if (self::$connection->connect_errno) {
            throw new RuntimeException('Database connection failed');
        }
    }

    public function query(string $sql, array $params = [], string $className = 'stdClass'): Iterator
    {
        $params        = self::escapeParams($params);
        $executableSQL = sprintf($sql, ...$params);
        $result        = self::$connection->query($executableSQL);
        if (self::$connection->errno) {
            throw new RuntimeException(sprintf('Database query error: %d - %s', self::$connection->errno, self::$connection->error));
        }

        if (!$result instanceof \mysqli_result) {
            return new \EmptyIterator();
        }

        return new MysqliMappingIterator($result, $className);
    }

    public function getLastInsertId()
    {
        return self::$connection->insert_id;
    }

    private static function escapeParams(array $params)
    {
        return array_map(fn(string $p) => self::$connection->real_escape_string($p), $params);
    }
}