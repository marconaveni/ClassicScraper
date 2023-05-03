<?php

use classic\app\databases\DB;
use classic\app\src\GameDBSearch;

$conn = DB::dbConnection();
$genresDB[] = DB::getGenres($conn);

if($genresDB[0] === null) {

    $gdbs = new GameDBSearch();
    $genre = $gdbs->apiGetGenres();

    foreach ($genre as $genre) {
        $conn = DB::dbConnection();
        $result = DB::insertGenre($conn, $genre);
    }
    echo json_encode($genre);
    exit;
}

echo json_encode($genresDB);
