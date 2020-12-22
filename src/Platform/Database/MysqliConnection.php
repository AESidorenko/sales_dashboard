<?php

namespace App\Platform\Database;

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

    public function query(string $sql, array $params = [])
    {
        $executableSQL = sprintf($sql, ...$params);
        $sanitizedSQL  = self::$connection->real_escape_string($executableSQL);
        $result        = self::$connection->query($sanitizedSQL);
        if (self::$connection->errno) {
            throw new RuntimeException(sprintf('Database query error: %d - %s', self::$connection->errno, self::$connection->error));
        }

        return $result;
    }

    private static function escapeParams(array $params)
    {
        return array_map(fn(string $p) => self::$connection->real_escape_string($p), $params);
    }
}