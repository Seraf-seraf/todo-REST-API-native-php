<?php

namespace ToDo\Command\DatabaseCommand;

use Exception;
use NotesApp\Database\Database;

class RunSQLCommand
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function run($dsn, $user, $password, $filePath)
    {
        try {
            $this->db->connect($dsn, $user, $password);
            $this->db->runSql($filePath);

            echo "SQL file executed successfully.\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}