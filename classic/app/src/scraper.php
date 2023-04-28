<?php

namespace classic\app\src;

class Scraper
{
    private \DOMXPath $finder;

    public function loadHTML(string $link): void
    {
        libxml_use_internal_errors(true);
        //$html = file_get_contents($link);
        $html = $this->get_web_page($link);
        $DOM = new \DOMDocument();
        $DOM->loadHTML($html);
        $this->finder = new \DomXPath($DOM);
    }

    private function get_web_page($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $output = curl_exec($curl);
        curl_close($curl);
        
        return $output;
    }


    public function query(string $query)
    {
        $nodes = $this->finder->query($query); //example //a[@class='fancybox-thumb']/@href
        $array = [];
        foreach ($nodes as $node) {
            $array[] = $node->nodeValue;
        }
        return $array;
    }

    public function getDOM()
    {
        return $this->finder;
    }
}

?>


