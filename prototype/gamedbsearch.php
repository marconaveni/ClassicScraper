<?php


require_once "gamedbdetails.php";


class GameDBSearch
{

    public function scrapByGameName(string $name, int $platformId): array
    {
        $scraper = new Scraper;

        $scraper->loadHTML("https://thegamesdb.net/search.php?name=$name&platform_id[]=$platformId");
        $links = $scraper->query("//div[@class='col-6 col-md-2']/div/a/@href");
        
        foreach ($links as $id) {
            $value = explode("id=" , $id);
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



    function apiByPlatformID(int $plataformId,int $page = 1,string $apiPrivate): array
    {
        $url = "https://api.thegamesdb.net/v1/Games/ByPlatformID?apikey=$apiPrivate&id=6&fields=overview%20%2Crating%20%2Cpublishers%2Cplayers&include=boxart%2Cscreenshot&page=$page";
        $json = file_get_contents($url);
        $json = json_decode($json);

        //var_dump($json->pages);
        //var_dump($json->data->games);

        foreach ($json->data->games as $jgame) {
            $game = new Game();
            $game->id = $jgame->id;
            $game->title = $jgame->game_title;
            $game->releaseDate = $jgame->release_date;
            $game->publisher = $jgame->publishers[0];
            $game->developer = $jgame->developers[0];
            $game->players = $jgame->players;
            $game->cover = "https://cdn.thegamesdb.net/images/thumb/boxart/front/$jgame->id-1.jpg";
            $game->screenshot = "https://cdn.thegamesdb.net/images/thumb/screenshots/$jgame->id-1.jpg";
            $games[] = $game;
        }
        return $games;
    }


    function searchDescriptions(array $games) :array
    {
        $gdb = new GameDBDetails(); 
        foreach ($games as $game) {      

            $scraper = $gdb->loadHTML("https://thegamesdb.net/game.php?id=$game->id");
            $game = $gdb->getDescription($game,$scraper);      
        }
        return $games;
    }
}






$gdbs = new GameDBSearch();
$ids = $gdbs->scrapByGameName("Hello Pac-Man",6);

$games = $gdbs->scrapByGameID($ids[0]);

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
