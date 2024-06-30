<?php

namespace ToDo\Controller;

abstract class AbstractController
{
    public function responseJson($data, $statusCode = 200)
    {
        return json_encode([
            'body' => $data,
            'statusCode' => $statusCode,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    protected function getValues($fields): array
    {
        $data = [];

        foreach ($fields as $field) {
            $method = $_SERVER['REQUEST_METHOD'];

            $data[$field] = match ($method) {
                'POST', 'PATCH' => $_FILES[$field] ?? (isset($_POST[$field]) ? htmlspecialchars($_POST[$field]) : 'empty'),
                'PUT', 'GET' => isset($_GET[$field]) ? htmlspecialchars($_GET[$field]) : 'empty',
                default => 'empty',
            };
        }

        return array_filter($data, function ($value) {
            return $value !== 'empty';
        });
    }
}