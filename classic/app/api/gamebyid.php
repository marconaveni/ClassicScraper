<?php

use classic\app\databases\DB;
use classic\app\src\GameDBSearch;

$conn = DB::dbConnection();
$game = DB::getGameById($conn, $_GET['id']);

if(!isset($game->id)) {
    $gdbs = new GameDBSearch();
    $games[] = $gdbs->apiGetByGameID($_GET['id']);

    $game = DB::insertGame($conn, array_shift($games[0]));
}
echo json_encode($game);
