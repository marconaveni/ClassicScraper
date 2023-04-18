<?php 
/**
 * Summary of Scraper
 */
class Scraper
{
    
    private DOMXPath $finder;
    
    public function loadHTML(string $link) :void
    {
        libxml_use_internal_errors(true);
        $html = file_get_contents($link);
        $DOM = new DOMDocument();
        $DOM->loadHTML($html);     
        $this->finder = new DomXPath($DOM);
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
    
	public function getDOM() {       
		return $this->finder;
	}
}

?>


