<?php

namespace classic\app\databases;

use classic\app\config\config;

class DB
{
    public static function dbConnection()
    {
        $servername = config::getDotEnv("servername");
        $username = config::getDotEnv("username");
        $password = config::getDotEnv("password");

        try {

            $conn = new \PDO("mysql:host=$servername;dbname=GameDB", $username, $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $conn;

        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function getGameById($conn,$id)
    {
        $stmt = $conn->prepare('SELECT * FROM game WHERE id = :id');
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchAll();
        return $result;
    }

    public static function getGameByName($conn,$title)
    {
        $stmt = $conn->prepare('SELECT * FROM game WHERE title like %:title%');
        $stmt->execute(array('title' => $title));
        $result = $stmt->fetchAll();
        return $result;
    }
}
