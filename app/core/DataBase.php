<?php
class DataBase
{

    public static function connect($host = DB_HOST, $dbname = DB_DATABASE, $user = DB_USERNAME, $pass = DB_PASSWORD)
    {
        try {
            $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        } catch (PDOException $e) {
            die('Database error: ' . $e->getMessage() . '<br/>');
        }
        return $db;
    }
}