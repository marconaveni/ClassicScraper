<?php


use classic\app\src\GameDBSearch;
use classic\app\databases\DB;
use classic\app\src\Game;
use classic\app\src\Publisher;
use classic\app\src\Developer;
use classic\app\src\Genres;

spl_autoload_register(function ($classNome) {

    $classNome = strtolower($classNome);
    $classNome = str_replace('classic', '', $classNome);
    $classNome = str_replace('\\', DIRECTORY_SEPARATOR , $classNome . ".php");
    $dirPath = str_replace('public' , '', __DIR__);
    $classNome = implode('', explode(DIRECTORY_SEPARATOR, $classNome, 2));
  //  var_dump($classNome);
  //  var_dump($dirPath);

   // var_dump($dirPath . $classNome);

    
    if(!file_exists($dirPath . $classNome)) {
        echo "Arquivo \"" . $dirPath . $classNome ."\" não existe!";
        exit;
    }
    require_once $dirPath . $classNome;

});


//echo $_SERVER['REQUEST_URI'];

$gdbs = new GameDBSearch();
$ids = $gdbs->scrapByGameName("Super Mario World", 6);
//$games[] = $gdbs->apiByGameID($ids[0]);
$games[] = $gdbs->scrapByGameID($ids[0]);
echo json_encode($games);

exit;

$conn = DB::dbConnection();
$result = DB::getGameById($conn, 136);
//var_dump($result);

foreach ($result as $key) {
    $game = new Game();
    $developer = new Developer();
    $developer->id = $key['developers'];
    $publisher = new Publisher();
    $publisher->id = $key['publishers'];
    $genre = new Genres();
    $genre->id = $key['genres'];

    $game->id = $key['id'];   
    $game->title = $key['title'];   
    $game->description = $key['description'];   
    $game->developer = $developer;
    $game->publisher = $publisher;
    $game->genres = $genre;
    $game->releaseDate = $key['releasedate'];   
    $game->players = $key['players'];   
    $game->cover = $key['cover'];   
    $game->screenshot = $key['screenshot'];   


    //echo json_encode($game);
    //var_dump($game);
}



// $gdbs = new GameDBSearch();
// $ids = $gdbs->scrapByGameName("Hello Pac-Man", 6);
// $games = $gdbs->scrapByGameID($ids[0]);
// echo json_encode($games);




// $gdbs = new GameDBSearch();
// $games = $gdbs->apiByGameID(41);
// echo json_encode($games);




//$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = $game->getId() . "\n";
//fwrite($myfile, $txt);
//fclose($myfile);
//usort($a, [TestObj::class, "cmp_obj"]);

//echo json_encode();
