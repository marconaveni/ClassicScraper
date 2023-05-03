<?php

use classic\app\databases\DB;
use classic\app\src\GameDBSearch;

$conn = DB::dbConnection();
$plataformsDB[] = DB::getPlataforms($conn);

if($plataformsDB[0] === null) {

    $gdbs = new GameDBSearch();
    $plataforms = $gdbs->apiGetPlataforms();

    foreach ($plataforms as $plataform) {
        $conn = DB::dbConnection();
        $result = DB::insertPlataform($conn, $plataform);
    }
    echo json_encode($plataforms);
    exit;
}

echo json_encode($plataformsDB);
