<?php

namespace Core;

class Model extends Database
{
    protected static string $table = '';
    protected static $statement = null;
    /**
     * @return Model
     */
    public static function all(): Model
    {
        $sql = "SELECT * FROM " . static::$table;
        self::$statement = self::$connection->prepare($sql);
        self::$statement->execute();
        return new static;
    }
    /**
     * @return Model
     */
    public static function find(int $id): Model
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE id = :id";
        self::$statement = self::$connection->prepare($sql);
        self::$statement->execute(['id' => $id]);
        return new static;
    }
    /**
     * @param array<int,mixed> $data
     */
    public static function create(array $data): self
    {
        $sql = "INSERT INTO " . static::$table . " (";
        foreach ($data as $key => $value) {
            $sql .= $key . ',';
        }
        $sql = substr($sql, 0, -1) . ') VALUES (';
        foreach ($data as $key => $value) {
            $sql .= ':' . $key . ',';
        }
        $sql = substr($sql, 0, -1) . ')';
        $query = self::$connection->prepare($sql);
        $query->execute($data);
        return new static;
    }
    /**
     * @return Model
     * @param array<int,mixed> $data
     */
    public static function update(int $id, array $data): Model
    {
        $sql = "UPDATE " . static::$table . " SET ";
        foreach ($data as $key => $value) {
            $sql .= $key . ' = :' . $key . ',';
        }
        $sql = substr($sql, 0, -1) . ' WHERE id = :id';
        $query = self::$connection->prepare($sql);
        $data['id'] = $id;
        $query->execute($data);
        return new static;
    }
    /**
     * @return Model
     */
    public static function delete(int $id): Model
    {
        $sql = "DELETE FROM " . static::$table . " WHERE id = :id";
        self::$statement = self::$connection->prepare($sql);
        self::$statement->execute(['id' => $id]);
        return new static;
    }
    /**
     * @return Model
     */
    public static function where(string $key, string $operator, string $value): Model
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE " . $key . " " . $operator . " :value";
        self::$statement = self::$connection->prepare($sql);
        self::$statement->execute(['value' => $value]);
        return new static;
    }

    public static function get()
    {
        return self::$statement->fetch();
    }

    public static function getAll()
    {
        return self::$statement->fetchAll();
    }

    public static function first()
    {
        return self::$statement->fetchColumn();
    }

    /**
     * @return Model
     */
    public static function join(string $table, string $key, string $value): Model
    {
        $sql = "SELECT * FROM " . static::$table . " INNER JOIN " . $table . " ON " . static::$table . "." . $key . " = " . $table . "." . $value;
        self::$statement = self::$connection->prepare($sql);
        self::$statement->execute();
        return new static;
    }

    public static function groupBy(string $key)
    {
        $sql = "SELECT * FROM " . static::$table . " GROUP BY " . $key;
        self::$statement = self::$connection->prepare($sql);
        self::$statement->execute();
        return new static;
    }

    public static function orderBy(string $key, string $order = 'ASC')
    {
        $sql = static::$table . " ORDER BY " . $key . " " . $order;
        self::$statement = self::$connection->prepare($sql);
        self::$statement->execute();
        return new static;
    }

    public static function count()
    {
        return self::$statement->rowCount();
    }

    public static function execute($query_string, $params)
    {

        $query = self::$connection->prepare($query_string);

        $query->execute($params);
        return new static;
    }
}
