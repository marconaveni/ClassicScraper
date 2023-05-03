<?php

namespace classic\app\databases;

use classic\app\config\config;
use classic\app\src\Developer;
use classic\app\src\Game;
use classic\app\src\Genres;
use classic\app\src\Plataform;
use classic\app\src\Publisher;

class DB
{
    public static function dbConnection()
    {
        $servername = config::getDotEnv("servername");
        $username = config::getDotEnv("username");
        $password = config::getDotEnv("password");
        $database = config::getDotEnv("database");

        try {

            $conn = new \PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $conn;

        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }


    public static function getGameByName(\PDO $conn, string $title, string $plataform, int &$count)
    {
        $stmt = $conn->prepare('SELECT * FROM game WHERE title like :title AND plataforms = :plataform ORDER BY title,id');
        $stmt->execute(array('title' => "$title%",'plataform' => $plataform));
        // $stmt->execute(array('plataform' => $plataform));
        $result = $stmt->fetchAll();

        $games = array();
        foreach ($result as $key) {
            $game = new Game();
            $developer = new Developer();
            $publisher = new Publisher();
            $genre = new Genres();

            if(isset($key['developers'])) {
                $developer = DB::getDevelopersById($conn, $key['developers']);
                $game->developer = $developer;
            }
            if(isset($key['publishers'])) {
                $publisher = DB::getPublisherById($conn, $key['publishers']);
                $game->publisher = $publisher;
            }
            if(isset($key['genres'])) {
                $genre = DB::getGenresById($conn, $key['genres']);
                $game->genres = $genre;
            }

            if(isset($key['id'])) {
                $game->id = $key['id'];
            }
            if(isset($key['title'])) {
                $game->title = $key['title'];
            }
            if(isset($key['description'])) {
                $game->description = $key['description'];
            }
            if(isset($key['releasedate'])) {
                $game->releaseDate = $key['releasedate'];
            }
            if(isset($key['players'])) {
                $game->players = $key['players'];
            }
            if(isset($key['cover'])) {
                $game->cover = $key['cover'];
            }
            if(isset($key['screenshot'])) {
                $game->screenshot = $key['screenshot'];
            }
            $games[] = $game;
        }

        $count = sizeof($games);
        return $games;
    }

    public static function getGameById(\PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM game WHERE id = :id');
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchAll();

        $game = new Game();
        foreach ($result as $key) {
            $developer = new Developer();
            $publisher = new Publisher();
            $genre = new Genres();

            if(isset($key['developers'])) {
                $developer = DB::getDevelopersById($conn, $key['developers']);
                $game->developer = $developer;
            }
            if(isset($key['publishers'])) {
                $publisher = DB::getPublisherById($conn, $key['publishers']);
                $game->publisher = $publisher;
            }
            if(isset($key['genres'])) {
                $genre = DB::getGenresById($conn, $key['genres']);
                $game->genres = $genre;
            }

            if(isset($key['id'])) {
                $game->id = $key['id'];
            }
            if(isset($key['title'])) {
                $game->title = $key['title'];
            }
            if(isset($key['description'])) {
                $game->description = $key['description'];
            }
            if(isset($key['releasedate'])) {
                $game->releaseDate = $key['releasedate'];
            }
            if(isset($key['players'])) {
                $game->players = $key['players'];
            }
            if(isset($key['cover'])) {
                $game->cover = $key['cover'];
            }
            if(isset($key['screenshot'])) {
                $game->screenshot = $key['screenshot'];
            }
        }
        return $game;
    }

    public static function getPlataformById(\PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM plataform WHERE id = :id');
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchAll();

        $plataform = new Plataform();
        foreach ($result as $key) {
            $plataform->id = $key['id'];
            $plataform->name = $key['name'];
            $plataform->alias = $key['alias'];
        }
        return $plataform;
    }

    public static function getPlataforms(\PDO $conn): array
    {
        $stmt = $conn->prepare('SELECT * FROM plataform');
        $stmt->execute();
        $result = $stmt->fetchAll();


        foreach ($result as $key) {
            $plataform = new Plataform();
            $plataform->id = $key['id'];
            $plataform->name = $key['name'];
            $plataform->alias = $key['alias'];
            $plataforms[] = $plataform;
        }
        return (isset($plataforms)) ? $plataforms : null;
    }


    public static function getDevelopersById(\PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM developer WHERE id = :id');
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchAll();

        $developer = new Developer();
        foreach ($result as $key) {
            $developer->id = $key['id'];
            $developer->name = $key['name'];
        }
        return $developer;
    }

    public static function getDevelopers(\PDO $conn): mixed
    {
        //$stmt = $conn->prepare('SELECT * FROM developer WHERE id = \'1234566677\' ');
        $stmt = $conn->prepare('SELECT * FROM developer');
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach ($result as $key) {
            $developer = new Developer();
            $developer->id = $key['id'];
            $developer->name = $key['name'];
            $developers[] = $developer;
        }
        return (isset($developers)) ? $developers : null;
    }


    public static function getPublisherById(\PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM publisher WHERE id = :id');
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchAll();

        $publisher = new Publisher();
        foreach ($result as $key) {
            $publisher->id = $key['id'];
            $publisher->name = $key['name'];
        }
        return $publisher;
    }

    public static function getPublishers(\PDO $conn): mixed
    {
        $stmt = $conn->prepare('SELECT * FROM publisher');
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach ($result as $key) {
            $publisher = new Publisher();
            $publisher->id = $key['id'];
            $publisher->name = $key['name'];
            $publishers[] = $publisher;
        }
        return (isset($publishers)) ? $publishers : null;
    }


    public static function getGenresById(\PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM genre WHERE id = :id');
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchAll();

        $genre = new Genres();
        foreach ($result as $key) {
            $genre->id = $key['id'];
            $genre->name = $key['name'];
        }
        return $genre;
    }

    public static function getGenres(\PDO $conn): mixed
    {
        $stmt = $conn->prepare('SELECT * FROM genre');
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach ($result as $key) {
            $genre = new Genres();
            $genre->id = $key['id'];
            $genre->name = $key['name'];
            $genres[] = $genre;
        }
        return (isset($genres)) ? $genres : null;
    }




    public static function insertGame(\PDO $conn, Game $game)
    {
        try {
            $stmt = $conn->prepare(
                'INSERT INTO game (`id`,`title`,`description`,`developers`,`publishers`,`releasedate`,`players`,`genres`,`cover`,`screenshot`,`plataforms`)                    
                 VALUES (:id,:title,:description,:developers,:publishers,:releasedate,:players,:genres,:cover,:screenshot,:plataforms) '
            );



            $stmt->execute(array(
              ':id' => $game->id,
              ':title' => $game->title,
              ':description' => $game->description,
              ':developers' => $game->developer->id ?? null,
              ':publishers' => $game->publisher->id ?? null,
              ':releasedate' => $game->releaseDate,
              ':players' => $game->players,
              ':genres' => $game->genres->id ?? null,
              ':cover' => $game->cover ?? null,
              ':screenshot' => $game->screenshot ?? null,
              ':plataforms' => $game->platform->id ?? null
            ));

            //echo $stmt->rowCount();
        } catch(\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    public static function insertPublisher(\PDO $conn, Publisher $publisher)
    {
        try {
            $stmt = $conn->prepare(
                'INSERT INTO publisher (`id`,`name`)                    
                 VALUES (:id,:name) '
            );
            $stmt->execute(array(
              ':id' => $publisher->id,
              ':name' => $publisher->name
            ));

            echo $stmt->rowCount();
        } catch(\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public static function insertDeveloper(\PDO $conn, Developer $developer)
    {
        try {
            $stmt = $conn->prepare(
                'INSERT INTO developer (`id`,`name`)                    
                 VALUES (:id,:name) '
            );
            $stmt->execute(array(
              ':id' => $developer->id,
              ':name' => $developer->name
            ));

            echo $stmt->rowCount();
        } catch(\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    public static function insertGenre(\PDO $conn, Genres $genres)
    {
        try {
            $stmt = $conn->prepare(
                'INSERT INTO genre (`id`,`name`)                    
                 VALUES (:id,:name) '
            );
            $stmt->execute(array(
              ':id' => $genres->id,
              ':name' => $genres->name
            ));

            echo $stmt->rowCount();
        } catch(\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    public static function insertPlataform(\PDO $conn, Plataform $platarform)
    {
        try {
            $stmt = $conn->prepare(
                'INSERT INTO plataform (`id`,`name`,`alias`)                    
                 VALUES (:id,:name,:alias) '
            );
            $stmt->execute(array(
              ':id' => $platarform->id,
              ':name' => $platarform->name,
              ':alias' => $platarform->alias
            ));

            echo $stmt->rowCount();
        } catch(\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
