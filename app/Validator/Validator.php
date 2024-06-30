<?php

namespace ToDo\Validator;

use DateTime;

class Validator
{
    public static function validate(&$fields, $rules): array
    {
        $errors = [];


        foreach ($fields as $field => $value) {
            $field_rules = $rules[$field];

            foreach ($field_rules as $type => $constraint) {
                if ($constraint == 'required' && empty($value)) {
                    $errors[$field][] = "Атрибут $field обязателен для заполнения!";
                }
                if ($constraint == 'string' && !is_string($value)) {
                    $errors[$field][] = "Атрибут $field должен быть строкой!";
                }
                if ($constraint == 'boolean' && !static::isBoolean($value)) {
                    $errors[$field][] = "Атрибут $field должен быть boolean: 0 или 1!";
                }
                if ($type == 'length.min' && is_string($value) && mb_strlen($value) < $constraint) {
                    $errors[$field][] = "Атрибут $field должен быть длиной минимум $constraint символов";
                }
                if ($type == 'length.max' && is_string($value) && mb_strlen($value) > $constraint) {
                    $errors[$field][] = "Атрибут $field не должен быть длиной больше $constraint символов";
                }
                if ($constraint == 'date' && !static::validateDate($value)) {
                    $errors[$field][] = "Атрибут $field должен содержать корректную дату в формате Y-m-d H:i:s";
                }
            }
        }

        return $errors;
    }



    public static function validateDate($date, $format = 'Y-m-d H:i:s'): bool
    {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        return $d && $d->format('Y-m-d H:i:s') === $date;
    }

    public static function isBoolean($value): bool
    {
        return match ($value) {
            0, 1, '0', '1' => true,
            default => false,
        };
    }
}