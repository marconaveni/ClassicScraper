<?php

use classic\app\config\config;
use classic\app\databases\DB;
use classic\app\src\GameDBDetails;
use classic\app\src\GameDBSearch;
use classic\app\src\Helpers;

function getDBPlatforms()
{
    $conn = DB::dbConnection(config::getDotEnv("database"));

    $resultsgames = DB::getGame($conn, $_GET['title'], $_GET['plataformid']);
    $type = "Database";

    if(sizeof($resultsgames) == 0) {
        $gdbs = new GameDBSearch();
        $details = new GameDBDetails();
        $ids = $gdbs->scrapByGameName($_GET['title'], $_GET['plataformid']);
        foreach ($ids as $id) {
            $resultsgames[] = $details->loadGameDetails($id);
        }
        $type = "Site";
    }

    echo "{\"code\":" . http_response_code() .",\"status\":\"Success\",\"type\":\"" . $type . "\",\"search\":\"" . $_GET['title'] .  "\",\"games\":" . json_encode($resultsgames) . "}";
    exit;

}


if(!isset($_GET['title']) || !isset($_GET['plataformid'])) {
    http_response_code(400);
    echo "{\"code\":" . http_response_code() .",\"status\":\"Parameters not found\"}";
    exit;
}

$platformId = (int)$_GET['plataformid'] ;
if($platformId == 0) {
    http_response_code(400);
    echo "{\"code\":" . http_response_code() .",\"status\":\"platformid invalid\"}";
    exit;
}


$_GET['title'] = Helpers::setTitle($_GET['title']);
getDBPlatforms();
