<?php

namespace App\Core;

use mysqli;

require_once __DIR__ . '/../../config/config.php';

class Database
{
    private mysqli $connection;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        $this->connection = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_NAME
        );

        if ($this->connection->connect_error) {
            die('Connection failed: ' . $this->connection->connect_error);
        }
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}