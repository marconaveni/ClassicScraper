<?php 


require_once "autoloader.php";

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if(strpos($path, 'api') !== false) {
    header('Content-Type: application/json; charset=utf-8');
}
if(strpos($path, 'gamebyid') !== false) {              /* /gamebyid?id={int}                    */
    require_once "../app/api/gamebyid.php";
} elseif(strpos($path, 'gamebyname') !== false) {      /* /gamebyname?plataformid={int}&title={string}   */
    require_once "../app/api/gamebyname.php";
} elseif(strpos($path, 'platforms') !== false) {       /* /platforms                           */
    require_once "../app/api/platforms.php";
} elseif(strpos($path, 'genres') !== false) {          /* /genres                               */
    require_once "../app/api/genres.php";
}
else{
    echo "page";
}
    

?>