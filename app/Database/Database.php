<?php

namespace ToDo\Database;

use PDO;

class Database
{
    private static $instance;
    public PDO $db;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function connect($dsn, $user, $password)
    {
        return $this->db = new PDO($dsn, $user, $password);
    }
}