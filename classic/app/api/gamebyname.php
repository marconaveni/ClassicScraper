<?php

use classic\app\config\config;
use classic\app\databases\DB;
use classic\app\src\GameDBDetails;
use classic\app\src\GameDBSearch;

function getDBPlatforms()
{
    $conn = DB::dbConnection(config::getDotEnv("database"));

    $resultsgames = DB::getGame($conn, $_GET['title'], $_GET['plataformid']);

    if(sizeof($resultsgames) == 0) {
        $gdbs = new GameDBSearch();
        $details = new GameDBDetails();
        $ids = $gdbs->scrapByGameName($_GET['title'], $_GET['plataformid']);
        foreach ($ids as $id) {
            $resultsgames[] = $details->loadGameDetails($id);
        }
    }

    echo "{ \"code\":200 , \"status\":\"Success\" , \"games\":" . json_encode($resultsgames) . "}";
    exit;

}

function setTitle()
{
    $matches = array();
    $title = $_GET['title'];
    $title = str_replace(" - ", " ", $title);
    $title = str_replace(":", "", $title);
    $title = str_replace("!", "", $title);
    $title = preg_replace('/(\s\()([0-9a-zA-Z\,\s)(.]+)/', "", $title);
    preg_match('/, ([0-9a-zA-Z]{1,3})/', $title, $matches);
    $title = preg_replace('/, ([A-Za-z])([A-Za-z])([A-Za-z])?/', "", $title);
    if(isset($matches[1])) {
        $title = $matches[1] . " " . $title;
    }
    $_GET['title'] = trim($title);
}


setTitle();
getDBPlatforms();
