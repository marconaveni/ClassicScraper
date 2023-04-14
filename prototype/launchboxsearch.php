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
$html = file_get_contents("https://thegamesdb.net/search.php?name=Mario%20kart&platform_id[]=6");
$DOM = new DOMDocument();
$DOM->loadHTML($html);
$finder = new DomXPath($DOM);

$images = $finder->query("//div[@class='col-6 col-md-2']/div/a/@href");
$names = $finder->query("//div[@class='col-6 col-md-2']/div/a/div/div/p[1]");
$regions = $finder->query("//div[@class='col-6 col-md-2']/div/a/div/div/p[2]");

$size = sizeof($images);
$d = 0;
for ($i=0; $i < $size; $i++) {
    echo $images[$i]->nodeValue . "<br>";
    echo $names[$i]->nodeValue . "<br>";
    echo getRegion($regions[$i]->nodeValue) . "<br>";
    echo "<br>=====================<br>";
}

echo "<hr>";


foreach ($regions as $node) {
    echo $node->nodeValue . "<br>";
}


function getRegion(string $region): string
{
    if(strtoupper($region) == "NTSC" || strtoupper($region) == "PAL") {
        return strtoupper($region);
    }
    return "OTHER";
}



echo "<code>";
print_r($html);
echo "</code>";

?>

</body>
</html>



