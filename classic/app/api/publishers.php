<?php

use classic\app\databases\DB;
use classic\app\src\GameDBSearch;

$conn = DB::dbConnection();
$publisherDB[] = DB::getPublishers($conn);

if($publisherDB[0] === null) {

    $gdbs = new GameDBSearch();
    $publisher = $gdbs->apiGetPublishers();

    foreach ($publisher as $publisher) {
        $conn = DB::dbConnection();
        $result = DB::insertPublisher($conn, $publisher);
    }
    echo json_encode($publisher);
    exit;
}

echo json_encode($publisherDB);
