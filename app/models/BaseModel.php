<?php
abstract class BaseModel
{
    protected static $db = null; // подключение к базе данных
    protected static $dbTableName = ''; // название таблицы, с которой работает модель
    protected $user;
    protected $requiredFields;
    public function __construct()
    {
        if (!empty(Session::Get('user'))) {
            $this->user = Session::Get('user');
        }
    }

    public static function find($id)
    {
        $tableName = static::$dbTableName;
        $sql = "SELECT * FROM $tableName WHERE id = :id";
        $statement = self::getDB()->prepare($sql);
        $statement->bindParam('id', $id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    protected static function getDB()
    {
        if (!is_resource(self::$db)) {
            self::$db = DataBase::connect();
        }
        return self::$db;
    }

    public static function all()
    {
        $tableName = static::$dbTableName;
        $sql = "SELECT * FROM $tableName ORDER BY name;";
        $statement = self::getDB()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDbTableName()
    {
        return static::$dbTableName;
    }

    public static function destroy($id)
    {
        $id = (int)$id;
        if (is_int($id)) {
            $tableName = static::$dbTableName;
            $sql = "DELETE FROM $tableName WHERE id = :id;";
            $statement = self::getDB()->prepare($sql);
            $statement->bindParam('id', $id);
            $result = $statement->execute();
        } else {
            $result = false;
        }
        return $result;
    }

    protected static function getItem($name)
    {
        $tableName = static::$dbTableName;
        $sql = "SELECT * FROM $tableName WHERE name = :name LIMIT 1";
        $statement = self::getDB()->prepare($sql);
        $statement->bindParam('name', $name);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function validate($params)
    {
        foreach ($params as $key => $param) {
            foreach ($this->requiredFields as $field) {
                if ($key === $field && empty($params[$key])) {
                    return false;
                }
            }
        }
        return true;
    }

    public function getUserName()
    {
        return $this->getCurrentUser('name');
    }

   public function getCurrentUser($param = null)
    {

        $u=[];
        if (isset($param)) {
            return isset($this->$u[$param]) ? $this->$u[$param] : null;
        }
        return isset($this->user) ? $this->user : null;
    }

    public function getHash($password)
    {
        return md5($password);
    }
}