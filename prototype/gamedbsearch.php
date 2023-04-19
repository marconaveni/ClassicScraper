<?php



require_once "gamedbdetails.php";


class GameDBSearch
{
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

    public function scrapByGameID(int $id)
    {
        $gdb = new GameDBDetails();
        return  $gdb->loadGameDetails($id);
    }



    public function apiByPlatformID(string $apiPrivate,int $plataformId,int $page = 1 ): array
    {
        $url = "https://api.thegamesdb.net/v1/Games/ByPlatformID?apikey=$apiPrivate&id=$plataformId&fields=overview%20%2Crating%20%2Cpublishers%2Cplayers&include=boxart%2Cscreenshot&page=$page";
        $json = file_get_contents($url);
        $json = json_decode($json);

        //var_dump($json->pages);
        //var_dump($json->data->games);

        foreach ($json->data->games as $jgame) {
            $game = new Game();
            $publisher = new Publisher();
            $publisher->id = $jgame->publishers[0];          
            $developer = new Developer();
            $developer->id = $jgame->developers[0];

            $game->id = $jgame->id;
            $game->title = $jgame->game_title;
            $game->releaseDate = $jgame->release_date;         
            $game->publisher = $publisher;
            $game->developer = $developer;
            $game->players = $jgame->players;
            $game->cover = "https://cdn.thegamesdb.net/images/thumb/boxart/front/$jgame->id-1.jpg";
            $game->screenshot = "https://cdn.thegamesdb.net/images/thumb/screenshots/$jgame->id-1.jpg";
            $games[] = $game;
        }
        return $games;
    }


    public function searchDescriptions(array $games): array
    {
        $gdb = new GameDBDetails();
        foreach ($games as $game) {

            $scraper = $gdb->loadHTML("https://thegamesdb.net/game.php?id=$game->id");
            $game = $gdb->getDescription($game, $scraper);
        }
        return $games;
    }
}






// $gdbs = new GameDBSearch();
// $ids = $gdbs->scrapByGameName("Hello Pac-Man", 6);
// $games = $gdbs->scrapByGameID($ids[0]);
// echo json_encode($games);




require_once "config/config.php";
$gdbs = new GameDBSearch();
$games = $gdbs->apiByPlatformID(getDotEnv("PRIVATE_APIKEY"), 6 );
echo json_encode($games);


// $gdbs = new GameDBSearch();
// $ids = $gdbs->scrapByGameName("Super Mario World",6);
// $games[] = $gdbs->scrapByGameID($ids[0]);
// echo json_encode($games);


//$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = $game->getId() . "\n";
//fwrite($myfile, $txt);
//fclose($myfile);
//usort($a, [TestObj::class, "cmp_obj"]);

//echo json_encode();
