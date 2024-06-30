<?php

use ToDo\Controller\TasksController;

require_once 'app/app.php';

/**
 * @var string $uri $_SERVER['REQUEST_URI'] from app.php
 * @var string $method $_SERVER['REQUEST_METHOD'] from app.php
 * @var TasksController $tasksController
 */
$uriParts = explode('/', $uri);
$model = $uriParts[1];
$response = [];

try {
    if ($model === 'tasks') {
        $id = isset($uriParts[2]) ? intval($uriParts[2]) : null;
        if ($id) {
            $response = match ($method) {
                'GET' => $tasksController->show($id),
                'DELETE' => $tasksController->destroy($id),
                'PUT' => $tasksController->update($id),
                default => [
                    'error' => 'Method not allowed',
                    'statusCode' => 405,
                ]
            };
        } else {
            $response = match ($method) {
                'GET' => $tasksController->index(),
                'POST' => $tasksController->store(),
                default => [
                    'error' => 'Method not allowed',
                    'statusCode' => 405,
                ]
            };
        }
    }
} catch (\ToDo\Exception\NotFoundHttpException $e) {
    $response = [
        'error' => 'Page not found',
        'statusCode' => 404,
    ];
} catch (InvalidArgumentException $e) {
    $response = [
        'error' => $e->getMessage(),
        'statusCode' => 422,
    ];
} catch (Throwable $e) {
    $response = [
        'error' => $e->getMessage(),
        'statusCode' => 500
    ];
} finally {
    $statusCode = match (true) {
        is_string($response) => json_decode($response, true)['statusCode'],
        is_array($response) => $response['statusCode'],
        default => 500,
    };
    http_response_code($statusCode);
    header('Content-Type: application/json');

    if (is_array($response)) {
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        echo $response;
    }
}

