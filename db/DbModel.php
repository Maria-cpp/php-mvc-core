<?php


namespace zum\phpmvc\db;

use zum\phpmvc\Application;
use zum\phpmvc\Model;

abstract class DbModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function save(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = static::prepare("INSERT INTO $tableName(" . implode(',', $attributes) . ")
            VALUES(" . implode(',', $params) . ")");
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        
        return $statement->execute();;

    }


    public function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr, $value) => "$attr = '$value'", $attributes, $where));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");

        foreach ($where as $key => $item) {
            $statement->bindValue(":key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public function fetchAll()
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * FROM $tableName");
        $statement->execute();
        return $statement->fetchAll();
    }

    public function deleteOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr, $value) => "$attr = '$value'", $attributes, $where));
        $statement = self::prepare("Delete FROM $tableName WHERE $sql");

        foreach ($where as $key => $item) {
            $statement->bindValue(":key", $item);
        }
        return $statement->execute();
    }

    public function count()
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * FROM $tableName");
        $statement->execute();
        return $statement->rowCount();
    }

    public function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}