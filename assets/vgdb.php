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
libxml_use_internal_errors(true);
$html = file_get_contents("https://www.vgdb.com.br/mega-drive/jogos/x-men-/");
$DOM = new DOMDocument();
$DOM->loadHTML($html);



$finder = new DomXPath($DOM);


$classname = 'specs';
$nodes = $finder->query("//p");
// foreach ($nodes as $node) {
//     echo $node->nodeValue . "<br>";
// }
echo $nodes[5]->nodeValue . "<br>";

echo "<hr>";

$classname = 'specs';
$nodes = $finder->query("//ul[@class='specs']/li");
foreach ($nodes as $node) {
    echo $node->nodeValue . "<br>";
}

echo "<hr>";


$classname = 'galeria-item';
$nodes = $finder->query("//a[@class='galeria-item']/@href");
foreach ($nodes as $node) {
    echo $node->nodeValue . "<br>";
}

var_dump($nodes);

?>

</body>
</html>



