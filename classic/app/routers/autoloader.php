<?php 


spl_autoload_register(function ($classNome) {

    $classNome = strtolower($classNome);
    $classNome = str_replace('classic', '', $classNome);
    $classNome = str_replace('\\', DIRECTORY_SEPARATOR, $classNome . ".php");
    $dirPath = str_replace('app' . DIRECTORY_SEPARATOR .  'routers' , '', __DIR__);
    $classNome = implode('', explode(DIRECTORY_SEPARATOR, $classNome, 2));


    if(!file_exists($dirPath . $classNome)) {
        echo "Arquivo \"" . $dirPath . $classNome ."\" não existe!";
        exit;
    }
    require_once $dirPath . $classNome;

});
