<?php

use classic\app\config\config;
use classic\app\databases\DB;
use classic\app\src\GameDBSearch;

function getDBPlatforms()
{
    $conn = DB::dbConnection(config::getDotEnv("database"));

    $resultsgames = array();
    $resultsgames[] = DB::getGameID($conn, $_GET['id']);
    $type = "Database";

    if(!isset($resultsgames[0]->id)) {
        $resultsgames = array();
        $gdbs = new GameDBSearch();
        $resultsgames[] = $gdbs->scrapByGameID($_GET['id']);
        $type = "Site";
    }

    echo "{\"code\":" . http_response_code() .",\"status\":\"Success\",\"type\":\"" . $type . "\",\"games\":" . json_encode($resultsgames) . "}";
    exit;

}

if(!isset($_GET['id'])) {
    http_response_code(400);
    echo "{\"code\":" . http_response_code() .",\"status\":\"Parameter not found\"}";
    exit;
}

$platformId = (int)$_GET['id'] ;
if($platformId == 0) {
    http_response_code(400);
    echo "{\"code\":" . http_response_code() .",\"status\":\"id invalid\"}";
    exit;
}

getDBPlatforms();
