<?php

use classic\app\databases\DB;
use classic\app\src\GameDBSearch;

$conn = DB::dbConnection();
$plataform = DB::getPlataformById($conn, $_GET['id']);

if(!isset($plataform->id)) {

    $gdbs = new GameDBSearch();
    //$plataforms[] = $gdbs->apiGetByPlatformsID($_GET['id']);

    DB::insertPlataform($conn, $plataforms[0][0]);
    echo json_encode($plataforms);
    exit;
}
echo json_encode($plataform);
