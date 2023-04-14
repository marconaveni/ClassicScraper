<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


<?php

require_once 'game.php';

$id =(int)$_GET['id'] ?? 136;

if($id == 0) die();

libxml_use_internal_errors(true);
//$html = file_get_contents("https://thegamesdb.net/game.php?id=136");
$html = file_get_contents("https://thegamesdb.net/game.php?id=$id");
$DOM = new DOMDocument();
$DOM->loadHTML($html);


$finder = new DomXPath($DOM);
$game = new Game();



//imagens
$images = $finder->query("//a[@class='fancybox-thumb']/@href");
foreach ($images as $image) {
    echo $image->nodeValue . "<br>";
}

echo "<hr>";
$descriptions = $finder->query("//div[@class='card-body']/p");
foreach ($descriptions as $description) {
    echo $description->nodeValue . "<br>";
}

echo "<hr>";

var_dump($id);
$game->setId(1);
$game->setCover($images[0]->nodeValue);
$game->setDeveloper(formatDescription($descriptions[2]->nodeValue));
$game->setPublisher(formatDescription($descriptions[3]->nodeValue));
$game->setReleaseDate(formatDescription($descriptions[4]->nodeValue));
$game->setPlayers(formatDescription($descriptions[5]->nodeValue));
$game->setDescription(trim($descriptions[7]->nodeValue));
$game->setGenre(formatDescription($descriptions[10]->nodeValue));

//$game->setId();
var_dump($game);

echo $game->getDeveloper();

function formatDescription(string $value): string
{
    $value = explode(":" , $value);
    return trim($value[1]);
}



// $classname = 'specs';
// $nodes = $finder->query("//p");
// // foreach ($nodes as $node) {
// //     echo $node->nodeValue . "<br>";
// // }
// echo $nodes[5]->nodeValue . "<br>";

// echo "<hr>";

// $classname = 'specs';
// $nodes = $finder->query("//ul[@class='specs']/li");
// foreach ($nodes as $node) {
//     echo $node->nodeValue . "<br>";
// }

// echo "<hr>";


// $classname = 'galeria-item';
// $nodes = $finder->query("//a[@class='galeria-item']/@href");
// foreach ($nodes as $node) {
//     echo $node->nodeValue . "<br>";
// }

 

?>

</body>
</html>



