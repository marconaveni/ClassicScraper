<?php

use classic\app\config\config;
use classic\app\databases\DB;

function getDBPlatforms()
{
    $conn = DB::dbConnection();

    $resultsGenres = array();
    $resultsGenres[] = DB::getGenres($conn);

    if(sizeof($resultsGenres) == 0) {
        
    }

    echo "{\"code\":200,\"status\":\"Success\",\"genres\":" . json_encode($resultsGenres) . "}";
    exit;

}

getDBPlatforms();