<?php 


require_once "autoloader.php";

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


//header('Content-Type: application/json; charset=utf-8');
if(strpos($path, 'gamebyid') !== false) {              /* /gamebyid?id={int}                    */
    require_once "../app/api/gamebyid.php";
} elseif(strpos($path, 'gamebyname') !== false) {      /* /gamebyname?plataformid={int}&title={string}   */
    require_once "../app/api/gamebyname.php";
} elseif(strpos($path, 'plataformsbyid') !== false) {  /* /plataformsbyid?id={int}              */
    require_once "../app/api/plataformsbyid.php";
} elseif(strpos($path, 'plataforms') !== false) {      /* /plataforms                           */
    require_once "../app/api/plataforms.php";
} elseif(strpos($path, 'developers') !== false) {      /* /developers                           */
    require_once "../app/api/developers.php";
} elseif(strpos($path, 'publishers') !== false) {      /* /publishers                           */
    require_once "../app/api/publishers.php";
} elseif(strpos($path, 'genres') !== false) {          /* /genres                               */
    require_once "../app/api/genres.php";
}
    

?>