<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config.php';

use ToDo\Command\RunSQLCommand;
/**
 * @var string $dbPass Database password from config.php
 * @var string $dbUser Database username from config.php
 * @var string $dsn Data Source Name from config.php
 */
\ToDo\Database\Database::getInstance()->connect($dsn, $dbUser, $dbPass);

$runSQL = new RunSQLCommand();
try {
    $sqlFilePath = realpath(__DIR__ . "/../Database/db.sql");

    $runSQL->runSql($sqlFilePath);
} catch (Exception $e) {
    echo $e->getMessage();
}