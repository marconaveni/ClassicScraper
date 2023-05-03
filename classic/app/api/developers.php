<?php

use classic\app\databases\DB;
use classic\app\src\GameDBSearch;

$conn = DB::dbConnection();
$developersDB[] = DB::getDevelopers($conn);

if($developersDB[0] === null) {

    $gdbs = new GameDBSearch();
    $developers = $gdbs->apiGetDevelopers();

    foreach ($developers as $developer) {
        $conn = DB::dbConnection();
        $result = DB::insertDeveloper($conn, $developer);
    }
    echo json_encode($developers);
    exit;
}

echo json_encode($developersDB);
