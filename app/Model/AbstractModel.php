<?php
namespace ToDo\Model;

use Exception;
use InvalidArgumentException;
use PDO;
use ToDo\Database\Database;
use ToDo\Exception\NotFoundHttpException;

abstract class AbstractModel
{
    protected static $db;
    protected static $table;

    public static $fields = [];

    public static function init(): void
    {
        static::$db = Database::getInstance()->db;
    }

    public static function create(array $data)
    {
        $data = static::prepareData($data);

        $sql = "INSERT INTO " . static::$table . " (" . implode(", ", array_keys($data)) . ") VALUES (" . mb_substr(str_repeat('?, ', count($data)), 0, -2) . ")";
        $stmt = static::$db->prepare($sql);
        $stmt->execute(array_values($data));

        $id = static::$db->lastInsertId();
        return static::find($id);
    }

    public static function find($id)
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE id = ?";
        $stmt = static::$db->prepare($sql);
        $stmt->execute([$id]);

        $result = $stmt->fetchObject(static::class);

        if (!$result) {
            throw new NotFoundHttpException('Model not found!');
        }

        return $result;
    }

    public static function findAll()
    {
        $sql = "SELECT * FROM " . static::$table;
        $stmt = static::$db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function update(array $data, $id)
    {
        if (!static::find($id)) {
            throw new NotFoundHttpException('Model not found!');
        }
        if (empty($data)) {
            throw new InvalidArgumentException('Empty data is not allowed for update!');
        }

        $data = static::prepareData($data);

        $setClause = implode(" = ?, ", array_keys($data)) . " = ?";
        $sql = "UPDATE " . static::$table . " SET " . $setClause . " WHERE id = ?";
        $stmt = static::$db->prepare($sql);
        $params = array_merge(array_values($data), [$id]);
        $stmt->execute($params);

        return static::find($id);
    }

    public static function delete($id)
    {
        if (!static::find($id)) {
            throw new NotFoundHttpException('Model not found!');
        }
        $sql = "DELETE FROM " . static::$table . " WHERE id = ?";
        $stmt = static::$db->prepare($sql);
        $stmt->execute([$id]);
    }

    protected static function prepareData($data)
    {
        foreach ($data as &$value) {
            $value = htmlspecialchars($value);
        }

        return $data;
    }
}