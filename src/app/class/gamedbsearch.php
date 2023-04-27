<?php

namespace classic\app\class;

 //require_once "gamedbdetails.php";
 //require_once "../config/config.php";

 use classic\app\config\config;

class GameDBSearch
{
    private string $apiPrivate;

    public function __construct()
    {
        $this->apiPrivate = config::getDotEnv("PUBLIC_APIKEY");
    }

    public function scrapByGameName(string $name, int $platformId): array
    {
        $scraper = new Scraper();

        $scraper->loadHTML("https://thegamesdb.net/search.php?name=$name&platform_id[]=$platformId");
        $links = $scraper->query("//div[@class='col-6 col-md-2']/div/a/@href");

        foreach ($links as $id) {
            $value = explode("id=", $id);
            $ids[] = (int)$value[1] ?? 0;
        }

        sort($ids);
        return $ids;
    }

    public function scrapByGameID(int $id) : Game
    {
        $gdb = new GameDBDetails();
        return  $gdb->loadGameDetails($id);
    }


    public function apiByGameID(int $id): array
    {
        $apiPrivate = $this->apiPrivate;
        $url = "https://api.thegamesdb.net/v1/Games/ByGameID?apikey=$apiPrivate&id=$id&fields=overview%2Cpublishers%2Cdevelopers%2Cgenres%2Cplayers";
        $json = file_get_contents($url);
        $json = json_decode($json);

        return $this->setGame($json);
    }


    public function apiByPlatformID(int $plataformId,int $page = 1 ): array
    {
        $apiPrivate = $this->apiPrivate;
        $url = "https://api.thegamesdb.net/v1/Games/ByPlatformID?apikey=$apiPrivate&id=$plataformId&fields=overview%2Cpublishers%2Cdevelopers%2Cgenres%2Cplayers&page=$page";
        $json = file_get_contents($url);
        $json = json_decode($json);

        return $this->setGame($json);
    }

    private function setGame($json) : array
    {
        foreach ($json->data->games as $jgame) {
            $game = new Game();
            $publisher = new Publisher();
            $publisher->id = $jgame->publishers[0];          
            $developer = new Developer();
            $developer->id = $jgame->developers[0];
            $genres = new Genres();
            $genres->id = $jgame->genres[0];

            $game->id = $jgame->id;
            $game->title = $jgame->game_title;
            $game->releaseDate = $jgame->release_date;  
            $game->description = $jgame->overview;       
            $game->publisher = $publisher;
            $game->developer = $developer;
            $game->genres = $genres;
            $game->players = $jgame->players;
            $game->cover = "https://cdn.thegamesdb.net/images/thumb/boxart/front/$jgame->id-1.jpg";
            $game->screenshot = "https://cdn.thegamesdb.net/images/thumb/screenshots/$jgame->id-1.jpg";
            $games[] = $game;
        }
        return $games;
    }

}