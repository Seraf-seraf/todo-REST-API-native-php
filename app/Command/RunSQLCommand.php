<?php

namespace ToDo\Command;

use Exception;
use ToDo\Database\Database;

class RunSQLCommand
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->db;
    }

    public function runSql($filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("SQL file not found: " . $filePath);
        }
        $sql = file_get_contents($filePath);

        try {
            $this->db->exec($sql);
            echo 'Создание базы данных выполнено успешно!';
        } catch (Exception $e) {
            throw new Exception("Error executing SQL file: " . $e->getMessage());
        }
    }
}