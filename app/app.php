<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'config.php';

use ToDo\Controller\TasksController;

/**
 * @var string $dbPass Database password from config.php
 * @var string $dbUser Database username from config.php
 * @var string $dsn Data Source Name from config.php
 */

$db = \ToDo\Database\Database::getInstance()
    ->connect($dsn, $dbUser, $dbPass);

\ToDo\Model\Tasks::init();
$tasksController = new TasksController();
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
header('Content-Type: application/json');