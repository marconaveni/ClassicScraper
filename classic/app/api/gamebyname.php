<?php

use classic\app\databases\DB;
use classic\app\src\GameDBSearch;

$conn = DB::dbConnection();
$count = 0;
$resultsgames = DB::getGameByName($conn, $_GET['title'], $_GET['plataformid'], $count);

if($count > 0) {
    echo json_encode($resultsgames);
    exit;
}

$gdbs = new GameDBSearch();
$ids = $gdbs->scrapByGameName($_GET['title'], $_GET['plataformid']);

if(sizeof($ids) == 0){
    echo "not found";
}

$games = array();

foreach ($ids as $id) {
    //$games[] = $gdbs->scrapByGameID($id);
    $conn = DB::dbConnection();
    $game = DB::getGameById($conn, $id);
    if(!isset($game->id)) {
        $gdbs = new GameDBSearch();
        $gamesDB = array();
        if(!$gdbs->apiGetByGameID($gamesDB, $id)) {
            continue;
        }
        $game = array_shift($gamesDB);
        DB::insertGame($conn, $game);
    }
    $games[] = $game;
}
echo json_encode($games);
