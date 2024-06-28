<?php

namespace NotesApp\Database;

use Exception;
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

    public function runSql($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("SQL file not found: " . $filePath);
        }

        $sql = file_get_contents($filePath);

        try {
            $this->db->exec($sql);
        } catch (Exception $e) {
            throw new Exception("Error executing SQL file: " . $e->getMessage());
        }
    }
}