<?php

namespace ToDo\Model;

class Tasks extends AbstractModel
{
    protected static $table = 'tasks';

    public static $fields = [
        'title',
        'description',
        'due_date',
        'status'
    ];

    public static function rules(): array
    {
        return [
            'title' => [static::requiredIf(), 'string', 'length.min' => 1, 'length.max' => 64],
            'description' => ['string', 'length.max' => 128, 'nullable'],
            'due_date' => [static::requiredIf(), 'date'],
            'status' => ['boolean', 'nullable'],
        ];
    }

    private static function requiredIf()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST' ? 'required' : 'nullable';
    }
}