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
    public static function dbConnection(string $database = "")
    {
        $servername = config::getDotEnv("servername");
        $username = config::getDotEnv("username");
        $password = config::getDotEnv("password");
        if($database == "") {
            $database = config::getDotEnv("databaseGameDB");
        }
        $tgdb = config::getDotEnv("databasetgdb");


        try {

            $conn = new \PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $conn;

        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function getGameID(\PDO $conn, string $id)
    {
        $stmt = $conn->prepare('SELECT  `id`,`game_title`,`players`,`release_date`,`overview`,`platform`
                                FROM `games` 
                                WHERE `id` = :id_game       
                                ');

        $stmt->execute(array('id_game' => $id ));
        $result = $stmt->fetchAll();

        foreach ($result as $key) {
            $game = new Game();
            $cover = "";
            $screenshot = "";

            if(isset($key['id'])) {
                $game->genres = DB::getGenre($conn, $key['id']);
                $game->publisher = DB::getPublisher($conn, $key['id']);
                $game->developer = DB::getDeveloper($conn, $key['id']);
                $cover = DB::getCover($conn, $key['id']);
                $screenshot = DB::getScreenshot($conn, $key['id']);
                $game->id = $key['id'];
            }
            if(isset($key['game_title'])) {
                $game->title = $key['game_title'];
            }
            if(isset($key['players'])) {
                $game->players = $key['players'];
            }
            if(isset($key['release_date'])) {
                $game->releaseDate = $key['release_date'];
            }
            if(isset($key['overview'])) {
                $game->description = str_replace("&quot;", "", $key['overview']);
            }
            if($cover != "") {
                $game->cover = $cover;
            }
            if($screenshot != "") {
                $game->screenshot = $screenshot;
            }
            return $game;
        }
        
    }

    public static function getGame(\PDO $conn, string $title, string $platform, int $try = 1)
    {

        switch ($try) {
            case 1:
                $comparison = "=";
                $queryLeft = "";
                $queryRight = "";
                $searchTitle = $queryLeft . $title .$queryRight;
                break;
            case 2:
                $comparison = "LIKE";
                $queryLeft = "%";
                $queryRight = "";
                $searchTitle = $queryLeft . $title .$queryRight;
                break;
            case 3:
                $comparison = "LIKE";
                $queryLeft = "%";
                $queryRight = "%";
                $searchTitle = $queryLeft . $title .$queryRight;
                break;
            default:
                $comparison = "LIKE";
                $queryLeft = "%";
                $queryRight = "%";
                $searchTitle = $queryLeft . htmlspecialchars($title) .$queryRight;
                break;
        }

        $stmt = $conn->prepare('SELECT  `id`,`game_title`,`players`,`release_date`,`overview`,`platform`
                                FROM `games` 
                                WHERE ( REPLACE(REPLACE(`game_title` , \':\' , \'\' ) , \'!\', \'\')'. $comparison . ' :title 
                                OR `id` = (SELECT `games_id` FROM `games_alts` WHERE REPLACE(REPLACE(`name` , \':\' , \'\' ) , \'!\', \'\') LIKE :title LIMIT 1)  
                                ) AND `platform` = :platform LIMIT 20;         
                                ');


        $stmt->execute(array('title' => $searchTitle ,'platform' => $platform));
        $result = $stmt->fetchAll();

        $games = array();

        //echo "$try <hr>";

        if(sizeof($result) == 0 && $try < 4) {
            $games = DB::getGame($conn, $title, $platform, ++$try);
        }

        foreach ($result as $key) {
            $game = new Game();
            $cover = "";
            $screenshot = "";

            if(isset($key['id'])) {
                $game->genres = DB::getGenre($conn, $key['id']);
                $game->publisher = DB::getPublisher($conn, $key['id']);
                $game->developer = DB::getDeveloper($conn, $key['id']);
                $cover = DB::getCover($conn, $key['id']);
                $screenshot = DB::getScreenshot($conn, $key['id']);
                $game->id = $key['id'];
            }
            if(isset($key['game_title'])) {
                $game->title = $key['game_title'];
            }
            if(isset($key['players'])) {
                $game->players = $key['players'];
            }
            if(isset($key['release_date'])) {
                $game->releaseDate = $key['release_date'];
            }
            if(isset($key['overview'])) {
                $game->description = str_replace("&quot;", "", $key['overview']);
            }
            if($cover != "") {
                $game->cover = $cover;
            }
            if($screenshot != "") {
                $game->screenshot = $screenshot;
            }
            $games[] = $game;

        }
        return $games;
    }

    public static function getDeveloper(\PDO $conn, int $gameID)
    {
        $stmt = $conn->prepare('SELECT `d`.`name`
                                FROM `games_devs` AS `gd`
                                INNER JOIN `devs_list` AS `d` ON `gd`.`dev_id` = `d`.`id`
                                WHERE `gd`.`games_id` = :gameID;      
                                ');

        $stmt->execute(array('gameID' => $gameID));
        $result = $stmt->fetchAll();

        $developer = new Developer();
        $developer->name = "";
        foreach ($result as $key) {
            if($developer->name != "") {
                $developer->name .= "|";
            }
            if(isset($key['name'])) {
                $developer->name .= $key['name'];
            }
        }
        return $developer->name == "" ? new Developer() : $developer;
    }
    public static function getPublisher(\PDO $conn, int $gameID)
    {
        $stmt = $conn->prepare('SELECT `p`.`name`
                                FROM `games_pubs` AS `gp`
                                INNER JOIN `pubs_list` AS `p` ON `gp`.`pub_id` = `p`.`id`
                                WHERE `gp`.`games_id` = :gameID;       
        ');
        $stmt->execute(array('gameID' => $gameID));
        $result = $stmt->fetchAll();

        $publisher = new Publisher();
        $publisher->name = "";
        foreach ($result as $key) {
            if($publisher->name != "") {
                $publisher->name .= "|";
            }
            if(isset($key['name'])) {
                $publisher->name .= $key['name'];
            }
        }
        return $publisher->name == "" ? new Publisher() : $publisher;
    }


    public static function getGenre(\PDO $conn, int $gameID)
    {
        $stmt = $conn->prepare('SELECT `gr`.`genre`
                                FROM `games_genre` AS `gg`
                                INNER JOIN `genres` AS `gr` ON `gr`.`id` = `gg`.`genres_id`
                                WHERE `gg`.`games_id` = :gameID;        
                                ');

        $stmt->execute(array('gameID' => $gameID));
        $result = $stmt->fetchAll();

        $genre = new Genres();
        $genre->name = "";
        foreach ($result as $key) {
            if($genre->name != "") {
                $genre->name .= "|";
            }
            if(isset($key['genre'])) {
                $genre->name .= $key['genre'];
            }
        }
        return $genre->name == "" ? new Genres() : $genre;
    }


    public static function getGenres(\PDO $conn)
    {
        $stmt = $conn->prepare('SELECT `id`, `genre`
                                FROM `genres`
                                ORDER BY `genre`;  
                                ');

        $stmt->execute();
        $result = $stmt->fetchAll();

        $genres = array();
        foreach ($result as $key) {
                $genre = new Genres();
                $genre->id = $key['id'];
                $genre->name = $key['genre'];
                $genres[] = $genre;
        }
        return $genres;
    }

    public static function getCover(\PDO $conn, int $gameID)
    {
        $stmt = $conn->prepare('SELECT `filename`
                                FROM `banners`
                                WHERE `games_id` = :gameID AND `side` = \'front\';
                                ');

        $stmt->execute(array('gameID' => $gameID));
        $result = $stmt->fetchAll();

        foreach ($result as $key) {
            $cover = "https://cdn.thegamesdb.net/images/thumb/" . $key['filename'];
            break;
        }
        return $cover ?? "";

    }

    public static function getScreenshot(\PDO $conn, int $gameID)
    {
        $stmt = $conn->prepare('SELECT `filename`
        FROM `banners`
        WHERE `games_id` = :gameID
        AND (`type` = \'screenshot\' OR `type` = \'fanart\')
        ORDER BY `type` DESC;     
        ');

        $stmt->execute(array('gameID' => $gameID));
        $result = $stmt->fetchAll();

        foreach ($result as $key) {
            $screenshot = "https://cdn.thegamesdb.net/images/thumb/" . $key['filename'];
            break;
        }
        return $screenshot ?? "";
    }


    public static function getPlatforms(\PDO $conn)
    {
        $stmt = $conn->prepare('SELECT `id`, `name`, `alias`
                                FROM platforms ORDER BY `name`;       
                                ');

        $stmt->execute();
        $result = $stmt->fetchAll();

        $platforms = array();
        foreach ($result as $key) {
                $platform = new Plataform();
                $platform->id = $key['id'];
                $platform->name = $key['name'];
                $platform->alias = $key['alias'];
                $platforms[] = $platform;
        }
        return $platforms;
    }



/*



    ////////////////////////////////////
    public static function getGameByName(\PDO $conn, string $title, string $plataform, int &$count)
    {
        $stmt = $conn->prepare('SELECT * FROM game WHERE title like :title AND plataforms = :plataform ORDER BY id,title');
        $stmt->execute(array('title' => "%$title%",'plataform' => $plataform));
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


    */
}
