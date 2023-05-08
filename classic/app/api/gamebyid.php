<?php

use classic\app\config\config;
use classic\app\databases\DB;
use classic\app\src\GameDBDetails;
use classic\app\src\GameDBSearch;

function getDBPlatforms()
{
    $conn = DB::dbConnection(config::getDotEnv("database"));

    $resultsgames = array();
    $resultsgames[] = DB::getGameID($conn, $_GET['id']);

    if(!isset($resultsgames[0]->id)) {
        $resultsgames = array();
        $gdbs = new GameDBSearch();
        $details = new GameDBDetails();
        $resultsgames[] = $gdbs->scrapByGameID($_GET['id']);
    }

    echo "{ \"code\":200 , \"status\":\"Success\" , \"games\":" . json_encode($resultsgames) . "}";
    exit;

}

getDBPlatforms();
