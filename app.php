<?php

use ToDo\Controller\TasksController;

$tasksController = new TasksController();

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];