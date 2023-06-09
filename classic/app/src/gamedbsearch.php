<?php

namespace classic\app\src;

//require_once "gamedbdetails.php";
//require_once "../config/config.php";

use classic\app\config\config;
use classic\app\databases\DB;

class GameDBSearch
{
    private string $apiPrivate;

    public function __construct()
    {
        $this->apiPrivate = config::getDotEnv("PUBLIC_APIKEY");
    }

    public function scrapByGameName(string $name, int $platformId): array
    {
        $name = urlencode($name);
        $scraper = new Scraper();
        $scraper->loadHTML("https://thegamesdb.net/search.php?name=$name&platform_id[]=$platformId");
        $links = $scraper->query("//div[@class='col-6 col-md-2']/div/a/@href");

        $ids = array();
        foreach ($links as $id) {
            $value = explode("id=", $id);
            if (isset($value[1]) != null) {
                $ids[] = (int)$value[1] ?? 0;
            }
        }

        sort($ids);
        return $ids;
    }

    public function scrapByGameID(int $id): Game
    {
        $gdb = new GameDBDetails();
        return  $gdb->loadGameDetails($id);
    }


    public function apiGetByGameID(array &$games, int $id): bool
    {
        $apiPrivate = $this->apiPrivate;
        $url = "https://api.thegamesdb.net/v1/Games/ByGameID?apikey=$apiPrivate&id=$id&fields=overview%2Cpublishers%2Cdevelopers%2Cgenres%2Cplayers%2Cplatform&include=boxart";
        $json = file_get_contents($url);
        $json = json_decode($json);

        return $this->setGame($games, $json);
    }

    public function apiGetByGameName(array &$games, string $title, int $plataformID): bool
    {
        $apiPrivate = $this->apiPrivate;
        $title = urlencode($title);
        $url = "https://api.thegamesdb.net/v1/Games/ByGameName?apikey=$apiPrivate&name=$title&fields=overview%2Cpublishers%2Cdevelopers%2Cgenres%2Cplayers%2Cplatform&filter%5Bplatform%5D=$plataformID&include=boxart";
        $json = file_get_contents($url);
        $json = json_decode($json);

        return $this->setGame($games, $json);
    }

    public function apiGetPlataforms(): array
    {
        $apiPrivate = $this->apiPrivate;
        $url = "https://api.thegamesdb.net/v1/Platforms?apikey=$apiPrivate";
        $json = file_get_contents($url);
        $json = json_decode($json);

        return $this->setPlatforms($json);
    }

    public function apiGetDevelopers(): array
    {
        $apiPrivate = $this->apiPrivate;
        $url = "https://api.thegamesdb.net/v1/Developers?apikey=$apiPrivate";
        $json = file_get_contents($url);
        $json = json_decode($json);

        return $this->setDevelopers($json);
    }

    public function apiGetPublishers(): array
    {
        $apiPrivate = $this->apiPrivate;
        $url = "https://api.thegamesdb.net/v1/Publishers?apikey=$apiPrivate";
        $json = file_get_contents($url);
        $json = json_decode($json);

        return $this->setPublishers($json);
    }

    public function apiGetGenres(): array
    {
        $apiPrivate = $this->apiPrivate;
        $url = "https://api.thegamesdb.net/v1/Genres?apikey=$apiPrivate";
        $json = file_get_contents($url);
        $json = json_decode($json);

        return $this->setGenres($json);
    }

    public function apiGetByPlatformsID(int $plataformId, int $page = 1): array
    {
        $apiPrivate = $this->apiPrivate;
        $url = "https://api.thegamesdb.net/v1/Games/ByPlatformID?apikey=$apiPrivate&id=$plataformId&fields=overview%2Cpublishers%2Cdevelopers%2Cgenres%2Cplayers%2Cplatform&page=$page";
        $json = file_get_contents($url);
        $json = json_decode($json);

        return $this->setPlatforms($json);
    }



    private function setGame(array &$games, $json): bool
    {

        foreach ($json->data->games as $jgame) {

            $game = new Game();

            if(isset($jgame->publishers[0])) {
                $conn = DB::dbConnection();
                $game->publisher = DB::getPublisher($conn, $jgame->publishers[0]);
            }
            if(isset($jgame->developers[0])) {
                $conn = DB::dbConnection();
                $game->developer = DB::getDeveloper($conn, $jgame->developers[0]);
            }
            if(isset($jgame->genres[0])) {
                $conn = DB::dbConnection();
                $game->genres = DB::getGenre($conn, $jgame->genres[0]);
            }

            $platform = new Platform();
            $platform->id = $jgame->platform;

            $game->id = $jgame->id;
            $game->title = $jgame->game_title;
            $game->releaseDate = $jgame->release_date ?? "";
            $game->description = $jgame->overview ?? "";
            $game->players = $jgame->players ?? 1;
            if(isset($json->include->boxart->data->{$jgame->id}[0]->filename)) {
                $game->cover = "https://cdn.thegamesdb.net/images/thumb/" . $json->include->boxart->data->{$jgame->id}[0]->filename;
            }
            $game->screenshot = "https://cdn.thegamesdb.net/images/thumb/screenshots/$jgame->id-1.jpg";
            $game->platform = $platform;
            $games[] = $game;
        }

        return sizeof($games) > 0;

    }

    private function setPlatforms($json): array
    {
        foreach ($json->data->platforms as $jplatform) {
            $platform = new Platform();
            $platform->id = $jplatform->id;
            $platform->name = $jplatform->name;
            $platform->alias = $jplatform->alias;
            $platforms[] = $platform;
        }
        return $platforms;
    }

    private function setDevelopers($json): array
    {
        foreach ($json->data->developers as $jplatform) {
            $developer = new Developer();
            $developer->id = $jplatform->id;
            $developer->name = $jplatform->name;
            $developers[] = $developer;
        }
        return $developers;
    }

    private function setPublishers($json): array
    {
        foreach ($json->data->publishers as $jplatform) {
            $publisher = new Publisher();
            $publisher->id = $jplatform->id;
            $publisher->name = $jplatform->name;
            $publishers[] = $publisher;
        }
        return $publishers;
    }

    private function setGenres($json): array
    {
        foreach ($json->data->genres as $jplatform) {
            $genre = new Genres();
            $genre->id = $jplatform->id;
            $genre->name = $jplatform->name;
            $genres[] = $genre;
        }
        return $genres;
    }

}
