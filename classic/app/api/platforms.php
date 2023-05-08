<?php

use classic\app\config\config;
use classic\app\databases\DB;

function getDBPlatforms()
{
    $conn = DB::dbConnection(config::getDotEnv("database"));

    $resultsGenres = array();
    $resultsGenres[] = DB::getPlatforms($conn);

    if(sizeof($resultsGenres) == 0) {
        
    }

    echo "{ \"code\":200 , \"status\":\"Success\" , \"platforms\":" . json_encode($resultsGenres) . "}";
    exit;

}

getDBPlatforms();
